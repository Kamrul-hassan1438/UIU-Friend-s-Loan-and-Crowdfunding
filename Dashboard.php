<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch crowdfunding campaigns created by the logged-in user
$sql = "SELECT * FROM crowdfundings WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Fetch loan offers with lender name and total amount calculation
$sql_offers = "
    SELECT 
        loanoffers.offer_id,
        loanoffers.loan_id, 
        loanoffers.interest_rate, 
        loanoffers.installments, 
        loanoffers.due_date, 
        loanoffers.amount_offered, 
        loans.amount AS loan_amount,
        users.username
    FROM 
        loanoffers
    JOIN 
        loans ON loanoffers.loan_id = loans.loan_id
    JOIN 
        users ON loanoffers.lender_id = users.user_id
    WHERE 
        loans.user_id = ? AND loanoffers.status = 'pending' -- Ensure only pending offers are selected
    ORDER BY 
        loanoffers.created_at DESC";

$stmt = $conn->prepare($sql_offers);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$offers_result = $stmt->get_result();

// Fetch loans created by the user
$sql_user_loans = "SELECT loan_id, amount, expected_return_date, description, status FROM loans WHERE user_id = ?";
$stmt_user_loans = $conn->prepare($sql_user_loans);
$stmt_user_loans->bind_param("i", $user_id);
$stmt_user_loans->execute();
$user_loans_result = $stmt_user_loans->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/Dashboard.css">
    <style>
    .User_loan-card {
        background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque background */
        border-radius: 8px;
        margin: 10px 0; /* Space between cards */
        transition: background-color 0.3s ease;
    }

    .User_loan-card:hover {
        background-color: rgba(255, 255, 255, 0.8); /* Change background on hover */
    }

    .User_loan-card p {
        margin: 5px 0; /* Space between paragraphs */
        font-size: 16px; /* Increase font size */
    }
    .hidden-id {
    display: none; /* Hides the Loan ID */
}

</style>
</head>

<body>
    <div class="container">
        <header>
            <div>
                <img src="img/Brand Logo 1.png" alt="Brand Logo" id="logo" />
            </div>
            <nav>
                <a href="HomePage.php">Home</a>
                <a href="Donation.php">Crowdfunding</a>
                <a href="Reviews.php">Reviews</a>
                <a href="Dashboard.php" style="color: azure;">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Column -->
            <div class="left-column">
                <div class="section crowdfunding">
                    <h2>Your Crowdfunding Campaigns</h2>

                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="card">
                                <img src="<?php echo !empty($row['image']) ? $row['image'] : 'img/default-campaign.png'; ?>" alt="Crowdfunding Image">
                                <p><?php echo htmlspecialchars($row['title']); ?></p>
                                <button class="btn" onclick="window.location.href='click_details_crowdfunding.php?id=<?php echo $row['crowdfunding_id']; ?>'">Read More</button>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You have not created any crowdfunding campaigns yet.</p>
                    <?php } ?>
                </div>

                <div class="section loans">
                    <h2>On going loans</h2>
                    <div id="loan-taken">
                        <img src="img/Ellipse 3.png" alt="Loan Image">
                        <button class="btn">Read More</button>
                    </div>
                    <div id="loan-given">
                        <img src="img/Ellipse 3.png" alt="Loan Image">
                        <button class="btn">Read More</button>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <h2>Loan Offers</h2>

                <?php if ($offers_result->num_rows > 0) { ?>
                    <?php while ($offer = $offers_result->fetch_assoc()) { 
                        // Calculate total amount
                        $interest_amount = $offer['loan_amount'] * ($offer['interest_rate'] / 100);
                        $total_amount = $offer['loan_amount'] + $interest_amount;
                    ?>
                        <div class="loan-offer">
                            <p><strong>Lender Name:</strong> <?php echo htmlspecialchars($offer['username']); ?></p>
                            <p><strong>UIU ID:</strong> <?php echo htmlspecialchars($offer['uiu_id']); ?></p>
                            <p><strong>Interest:</strong> <?php echo htmlspecialchars($offer['interest_rate']); ?>%</p>
                            <p><strong>Asked Amount:</strong> <?php echo htmlspecialchars($offer['loan_amount']); ?> Taka</p>
                            <p><strong>Amount To Pay:</strong> <?php echo htmlspecialchars($total_amount); ?> Taka</p>
                            <p><strong>Installments:</strong> <?php echo htmlspecialchars($offer['installments']); ?></p>
                            <p><strong>Pay per Installment:</strong> 
        <?php 
        // Calculate pay per installment
        $pay_per_installment = $offer['installments'] > 0 ? $total_amount / $offer['installments'] : 0; 
        echo htmlspecialchars($pay_per_installment) . ' Taka'; 
        ?>
                            <p><strong>Final Due Date:</strong> <?php echo htmlspecialchars($offer['due_date']); ?></p>
                            <button class="btn" onclick="if(confirm('Are you sure you want to remove this offer?')){window.location.href='accept_offer.php?offer_id=<?php echo $offer['offer_id']; ?>';}">Accept</button>

    <button class="btn" onclick="if(confirm('Are you sure you want to remove this offer?')) { window.location.href='remove_offer.php?offer_id=<?php echo $offer['offer_id']; ?>'; }">Remove</button>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No loan offers received yet.</p>
                <?php } ?>

                <!-- Display Loans Created by User -->
                <h2>Your Loans</h2>
                <?php if ($user_loans_result->num_rows > 0) { ?>
                    <?php while ($loan = $user_loans_result->fetch_assoc()) { ?>
                        <div class="User_loan-card">
                        <p class="hidden-id"><strong>Loan ID:</strong> <?php echo htmlspecialchars($loan['loan_id']); ?></p>
    <p><strong>Amount:</strong> <?php echo htmlspecialchars($loan['amount']); ?> Taka</p>
    <p><strong>Expected Return Date:</strong> <?php echo htmlspecialchars($loan['expected_return_date']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($loan['description']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($loan['status']); ?></p>
    
    <form method="POST" action="remove_loan.php" style="display:inline;">
        <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($loan['loan_id']); ?>">
        <button type="submit" class="btn" onclick="return confirm('Are you sure you want to remove this loan?');">Remove</button>
    </form>
                    <?php } ?>
                <?php } else { ?>
                    <p>You have not created any loans yet.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
