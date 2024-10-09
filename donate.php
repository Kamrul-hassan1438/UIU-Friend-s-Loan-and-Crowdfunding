<?php 
// Start the session
session_start();

// Database connection settings
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

// Get the campaign ID from the URL
$campaign_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch campaign details to display the title or information
$query = "SELECT * FROM crowdfundings WHERE crowdfunding_id = $campaign_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $campaign = mysqli_fetch_assoc($result);
} else {
    die("Campaign not found.");
}

// Initialize message variables
$message = '';
$error = '';

// Handle the donation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donation_amount = isset($_POST['donation_amount']) ? floatval($_POST['donation_amount']) : 0.00;

    // Validate the donation amount
    if ($donation_amount > 0) {
        // Add the donation amount to the collected_amount in the database
        $update_query = "UPDATE crowdfundings SET collected_amount = collected_amount + $donation_amount WHERE crowdfunding_id = $campaign_id";
        
        if (mysqli_query($conn, $update_query)) {
            // Store the donation in the contributions table
            $contributor_id = $_SESSION['user_id']; // Assuming the user is logged in
            $insert_query = "INSERT INTO contributions (crowdfunding_id, contributor_id, contribution_amount) VALUES ($campaign_id, $contributor_id, $donation_amount)";
            
            if (mysqli_query($conn, $insert_query)) {
                $message = "Donation successful. Thank you for your contribution!";
            } else {
                $error = "Error recording contribution: " . mysqli_error($conn);
            }
        } else {
            $error = "Error updating the campaign: " . mysqli_error($conn);
        }
    } else {
        $error = "Please enter a valid donation amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to <?php echo htmlspecialchars($campaign['title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/payment.css">
    <style>
        input[type="number"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 1.2em;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
        }

        input[type="number"] {
            -moz-appearance: textfield; /* Hide the up/down arrows in Firefox */
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none; /* Hide the up/down arrows in WebKit browsers */
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Donate to: <?php echo htmlspecialchars($campaign['title']); ?></h1>
        <div class="icon-container">
            <!-- Payment method icons -->
            <span><img src="img/BKash_Logo_icon-700x662.png" alt="BKash"></span>
            <span><img src="img/credit-card-pic.png" alt="Credit Card"></span>
        </div>

        <!-- Donation form -->
        <form action="donate.php?id=<?php echo $campaign_id; ?>" method="POST">
            <label for="donation_amount">Enter Donation Amount:</label>
            <input type="number" name="donation_amount" id="donation_amount" placeholder="Amount in Taka" required>
            <button type="submit">Proceed with Donation</button>
        </form>

        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <a href="javascript:history.back()">BACK</a>

    </div>
</body>
</html>
