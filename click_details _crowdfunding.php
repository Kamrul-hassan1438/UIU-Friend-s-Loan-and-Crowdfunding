<?php
// Start the session to access user info
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

// Fetch campaign details from the database
$query = "SELECT * FROM crowdfundings WHERE crowdfunding_id = $campaign_id";
$result = mysqli_query($conn, $query);

// Check if the campaign exists
if ($result && mysqli_num_rows($result) > 0) {
    $campaign = mysqli_fetch_assoc($result);
} else {
    die("Campaign not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($campaign['title']); ?> - Details</title>

    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/click_details_crowdfunding.css" />
</head>
<body>
    <header>
        <div>
            <img src="img/Brand Logo 1.png" alt="Brand Logo" id="logo" />
        </div>
        <nav>
            <a href="HomePage.php">Home</a>
            <a href="Donation.php">Crowdfunding</a>
            <a href="Reviews.php">Reviews</a>
            <a href="Dashboard.php">Dashboard</a>
            <a href="login.php">Logout</a>
        </nav>
    </header>

    <div id="container1">
        <div id="child1">
            <!-- Campaign Image -->
            <img src="<?php echo !empty($campaign['image']) ? $campaign['image'] : 'img/default-campaign.png'; ?>" alt="Campaign Image" />
            <!-- Campaign Title -->
            <p><?php echo htmlspecialchars($campaign['title']); ?></p>
        </div>

        <div id="child2">
            <!-- Campaign Description -->
            <p>
                <?php echo htmlspecialchars($campaign['description']); ?>
                <br /><br />
                <!-- Link to donor history (if applicable) -->
                <a href="donor_history.php?id=<?php echo $campaign_id; ?>">See donor history</a>
            </p>
        </div>

        <div id="child3">
            <table>
                <tr>
                    <td colspan="2"><img src="img/Group 4.png" alt="Donate/Share Icon" /></td>
                </tr>
                <tr>
                    <!-- Share Button -->
                    <td><button onclick="shareCampaign('<?php echo $campaign['title']; ?>')">Share</button></td>
                    <!-- Donate Button -->
                    <td><button onclick="window.location.href='donate.php?id=<?php echo $campaign_id; ?>'">Donate</button></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        // Function to simulate sharing the campaign (you can modify this to actually share)
        function shareCampaign(title) {
            alert("Share this campaign: " + title);
        }
    </script>
</body>
</html>
