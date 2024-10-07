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
                <div class="loan-offer">
                    <p>Interest: 10%</p>
                    <p>Installment: %</p>
                    <p>Due Date: 12/5/25</p>
                    <button class="btn">Read More</button>
                </div>
                <div class="loan-offer">
                    <p>Interest: 10%</p>
                    <p>Installment: %</p>
                    <p>Due Date: 12/5/25</p>
                    <button class="btn">Read More</button>
                </div>
                <div class="loan-offer">
                    <p>Interest: 10%</p>
                    <p>Installment: %</p>
                    <p>Due Date: 12/5/25</p>
                    <button class="btn">Read More</button>
                </div>
                <div class="loan-offer">
                    <p>Interest: 10%</p>
                    <p>Installment: %</p>
                    <p>Due Date: 12/5/25</p>
                    <button class="btn">Read More</button>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
