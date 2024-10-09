<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        users.username,
        users.uiu_id
    FROM 
        loanoffers
    JOIN 
        loans ON loanoffers.loan_id = loans.loan_id
    JOIN 
        users ON loanoffers.lender_id = users.user_id
    WHERE 
        loans.user_id = ? AND loanoffers.status = 'pending' 
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
// Fetch accepted loan offers for loans created by the logged-in user, including lender's phone number
$sql_accepted_offers = "
    SELECT 
        loanoffers.offer_id,
        loanoffers.loan_id, 
        loanoffers.amount_offered, 
        loanoffers.interest_rate, 
        loanoffers.installments, 
        loanoffers.due_date, 
        loans.amount AS loan_amount,
        users.username AS lender_name,
        users.uiu_id,
        users.phone AS lender_phone -- Select the lender's phone number
    FROM 
        loanoffers
    JOIN 
        loans ON loanoffers.loan_id = loans.loan_id
    JOIN 
        users ON loanoffers.lender_id = users.user_id
    WHERE 
        loans.user_id = ? -- Only loans created by the logged-in user
        AND loanoffers.status = 'accepted' -- Only accepted offers

    ORDER BY 
        loanoffers.created_at DESC";

$stmt_accepted_offers = $conn->prepare($sql_accepted_offers);
$stmt_accepted_offers->bind_param("i", $user_id);
$stmt_accepted_offers->execute();
$accepted_offers_result = $stmt_accepted_offers->get_result();
// Fetch loan offers sent by the user that have been accepted
$sql_accepted_loans_by_user = "
    SELECT 
        loanoffers.offer_id, 
        loanoffers.loan_id, 
        loanoffers.amount_offered, 
        loanoffers.interest_rate, 
        loanoffers.installments, 
        loanoffers.due_date, 
        loans.amount AS loan_amount, 
        users.username AS borrower_name, 
        users.uiu_id
    FROM 
        loanoffers
    JOIN 
        loans ON loanoffers.loan_id = loans.loan_id
    JOIN 
        users ON loans.user_id = users.user_id
    WHERE 
        loanoffers.lender_id = ? 
        AND loanoffers.status = 'accepted'
        AND loanoffers.amount_offered != loans.amount
    ORDER BY 
        loanoffers.created_at DESC";

$stmt_accepted_loans_by_user = $conn->prepare($sql_accepted_loans_by_user);
$stmt_accepted_loans_by_user->bind_param("i", $user_id);
$stmt_accepted_loans_by_user->execute();
$accepted_loans_by_user_result = $stmt_accepted_loans_by_user->get_result();

// Fetch loan offers created by the user where amount_offered equals loan amount
$sql_user_loan_offers = "
    SELECT 
        loanoffers.offer_id, 
        loanoffers.loan_id, 
        loanoffers.amount_offered, 
        loanoffers.interest_rate, 
        loanoffers.installments, 
        loanoffers.due_date, 
        loans.amount AS loan_amount, 
        users.username AS loan_creator_name, 
        users.uiu_id AS loan_creator_uiu_id, 
        users.phone AS loan_creator_phone 
    FROM 
        loanoffers 
    JOIN 
        loans ON loanoffers.loan_id = loans.loan_id 
    JOIN 
        users ON loans.user_id = users.user_id 
    WHERE 
        loanoffers.lender_id = ? AND 
        loanoffers.status = 'accepted' AND 
        loanoffers.amount_offered = loans.amount
    ORDER BY 
        loanoffers.created_at DESC";


$stmt_user_loan_offers = $conn->prepare($sql_user_loan_offers);
$stmt_user_loan_offers->bind_param("i", $user_id);
$stmt_user_loan_offers->execute();
$user_loan_offers_result = $stmt_user_loan_offers->get_result();


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
.left-column {
    width: 55%; /* Adjusted to take up more space */
    background-color: rgba(255, 255, 255, 0.8);
}.right-column {
    width: 40%; /* Adjusted width */
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto; 
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
                <a href="HomePage.php" >Home</a>
                <a href="Donationpage-2.php">Crowdfunding</a>
                <a href="Dashboard.php"style="color: azure;">Dashboard</a>
                <a href="login.html">Logout</a>
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
                                <button class="btn" onclick="window.location.href='details_for_creator.php?id=<?php echo $row['crowdfunding_id']; ?>'">Read More</button>

                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You have not created any crowdfunding campaigns yet.</p>
                    <?php } ?>
                </div>

                <div class="section loans">
                    <h2>On going loans</h2>
                    <div id="loan-taken">
    <h2>Accepted Loan Offers</h2>

    <?php if ($accepted_offers_result->num_rows > 0) { ?>
        <?php while ($offer = $accepted_offers_result->fetch_assoc()) { 
            // Calculate the total repayment amount
            $interest_amount = $offer['loan_amount'] * ($offer['interest_rate'] / 100);
            $total_amount = $offer['loan_amount'] + $interest_amount;
        ?>
            <div class="loan-offer">
                <p><strong>Lender Name:</strong> <?php echo htmlspecialchars($offer['lender_name']); ?></p>
                <p><strong>UIU ID:</strong> <?php echo htmlspecialchars($offer['uiu_id']); ?></p>
                <p><strong>Interest Rate:</strong> <?php echo htmlspecialchars($offer['interest_rate']); ?>%</p>
                <p><strong>Loan Amount:</strong> <?php echo htmlspecialchars($offer['loan_amount']); ?> Taka</p>
                <p><strong>Amount to Pay Back:</strong> <?php echo htmlspecialchars($total_amount); ?> Taka</p>
                <p><strong>Installments:</strong> <?php echo htmlspecialchars($offer['installments']); ?></p>
                <p><strong>Due Date:</strong> <?php echo htmlspecialchars($offer['due_date']); ?></p>
                <p><strong>Lender Phone Number:</strong> <?php echo htmlspecialchars($offer['lender_phone']); ?></p>

                <?php if ($offer['amount_offered'] != $offer['loan_amount']) { ?>
                    <p>Status: Amount sent not yet</p>
                <?php } else { ?>
                    <p><strong>When Your Instalment is done Make Sure Your Your Loan Giver End loan Status</strong></p>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No accepted loan offers found for your loans.</p>
    <?php } ?>
</div>




<div id="loan-given">
    <h2>Your Accepted Loan Offers</h2>

    <?php if ($user_loan_offers_result->num_rows > 0) { ?>
        <?php while ($loan_offer = $user_loan_offers_result->fetch_assoc()) { 
            $interest_amount = $loan_offer['interest_rate'] * $loan_offer['amount_offered'] / 100; 
            $total_amount = $loan_offer['amount_offered'] + $interest_amount; 
        ?>
            <div class="loan-offer">
                <p><strong>Amount Given:</strong> <?php echo htmlspecialchars($loan_offer['amount_offered']); ?> Taka</p>
                <p><strong>Interest Rate:</strong> <?php echo htmlspecialchars($loan_offer['interest_rate']); ?>%</p>
                <p><strong>Installments:</strong> <?php echo htmlspecialchars($loan_offer['installments']); ?></p>
                <p><strong>Due Date:</strong> <?php echo htmlspecialchars($loan_offer['due_date']); ?></p>
                <p><strong>Loan Creator Name:</strong> <?php echo htmlspecialchars($loan_offer['loan_creator_name']); ?></p>
                <p><strong>Loan Creator UIU ID:</strong> <?php echo htmlspecialchars($loan_offer['loan_creator_uiu_id']); ?></p>
                <p><strong>Loan Creator Phone Number:</strong> <?php echo htmlspecialchars($loan_offer['loan_creator_phone']); ?></p>
                <p><strong>Total Amount (Including Interest):</strong> <?php echo htmlspecialchars($total_amount); ?> Taka</p>
                
                <button class="btn" onclick="if(confirm('Are you sure you want to view this offer?')){window.location.href='Repayments.php?offer_id=<?php echo $loan_offer['offer_id']; ?>';}">End The Loan</button>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No accepted loan offers found that match the loan amounts.</p>
    <?php } ?>
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
        $pay_per_installment = $offer['installments'] > 0 ? $total_amount / $offer['installments'] : 0; 
        echo htmlspecialchars($pay_per_installment) . ' Taka'; 
        ?>
                            <p><strong>Final Due Date:</strong> <?php echo htmlspecialchars($offer['due_date']); ?></p>
                            <button class="btn" onclick="if(confirm('Are you sure you want to accept this offer?')) { 
    window.location.href='accept_offer.php?offer_id=<?php echo htmlspecialchars($offer['offer_id']); ?>'; 
}">Accept</button>


    <button class="btn" onclick="if(confirm('Are you sure you want to remove this offer?')) { window.location.href='remove_offer.php?offer_id=<?php echo $offer['offer_id']; ?>'; }">Remove</button>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No loan offers received yet.</p>
                <?php } ?>
                <h2>Your Loans</h2>
<?php 
$sql = "SELECT * FROM loans WHERE user_id = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_loans_result = $stmt->get_result();

if ($user_loans_result->num_rows > 0) { ?>
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
        </div>
    <?php } ?>
<?php } else { ?>
    <p>You have not created any loans yet.</p>
<?php } ?>






                <h2>Your Accepted offers</h2>

<?php if ($accepted_loans_by_user_result->num_rows > 0) { ?>
    <?php while ($loan_offer = $accepted_loans_by_user_result->fetch_assoc()) { 
        $interest_amount = $loan_offer['loan_amount'] * ($loan_offer['interest_rate'] / 100);
        $total_amount = $loan_offer['loan_amount'] + $interest_amount;
    ?>
        <div class="User_loan-card">
            <p><strong>Borrower Name:</strong> <?php echo htmlspecialchars($loan_offer['borrower_name']); ?></p>
            <p><strong>UIU ID:</strong> <?php echo htmlspecialchars($loan_offer['uiu_id']); ?></p>
            <p><strong>Loan Amount:</strong> <?php echo htmlspecialchars($loan_offer['loan_amount']); ?> Taka</p>
            <p><strong>Amount Offered:</strong> <?php echo htmlspecialchars($loan_offer['amount_offered']); ?> Taka</p>
            <p><strong>Interest Rate:</strong> <?php echo htmlspecialchars($loan_offer['interest_rate']); ?>%</p>
            <p><strong>Total Amount to Receive:</strong> <?php echo htmlspecialchars($total_amount); ?> Taka</p>
            <p><strong>Installments:</strong> <?php echo htmlspecialchars($loan_offer['installments']); ?></p>
            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($loan_offer['due_date']); ?></p>
            <button class="btn" onclick="if(confirm('Are you sure you want to send the payment?')) {window.location.href='sent_payment.php?offer_id=<?php echo $loan_offer['offer_id']; ?>'}">Sent Payment</button>

        </div>
    <?php } ?>
<?php } else { ?>
    <p>Your Any offer is not accepeted Yet.</p>
<?php } ?>

            </div>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>
