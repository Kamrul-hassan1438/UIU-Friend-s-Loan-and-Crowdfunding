<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the campaign ID from the URL
$campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch donor history from contributions table
$sql = "SELECT u.username, c.contribution_date, c.contribution_amount 
        FROM contributions c 
        JOIN users u ON c.contributor_id = u.user_id 
        WHERE c.crowdfunding_id = ? 
        ORDER BY c.contribution_amount DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$result = $stmt->get_result();

$contributions = [];
while ($row = $result->fetch_assoc()) {
    $contributions[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Donor History</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/donorHistory.css">
</head>
<body>
    <header>
        <div>
            <img src="img/Brand Logo 1.png" alt="Brand Logo" id="logo" />
        </div>
        <nav>
            <a href="HomePage.php">Home</a>
            <a href="Donation.html" style="color: azure;">Crowdfunding</a>
            <a href="Reviews.html">Reviews</a>
            <a href="Dashboard.php">Dashboard</a>
            <a href="login.html">Logout</a>
        </nav>
    </header>
    <div id="container1">
        <div id="container2">
            <h1>Donor History</h1>
            <table>
                <tr> 
                    <th>Username</th> 
                    <th>Contribution Amount (Taka)</th>
                    <th>Time</th>
                </tr>
                <?php if (empty($contributions)): ?>
                    <tr>
                        <td colspan="3">No contributions found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contributions as $contribution): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contribution['username']); ?></td>
                            <td><?php echo htmlspecialchars($contribution['contribution_amount']); ?> Taka</td>
                            <td><?php echo htmlspecialchars($contribution['contribution_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        
    </div>
</body>
</html>
