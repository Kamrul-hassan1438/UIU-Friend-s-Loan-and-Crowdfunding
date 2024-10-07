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

// Get the offer ID from the URL
if (isset($_GET['offer_id'])) {
    $offer_id = intval($_GET['offer_id']); // Ensure it's an integer

    // Prepare the SQL statement to delete the offer
    $sql = "DELETE FROM loanoffers WHERE offer_id = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    
    if ($stmt->execute()) {
        // Offer removed successfully
        header("Location: Dashboard.php?message=Offer removed successfully");
    } else {
        // Error removing the offer
        header("Location: Dashboard.php?error=Error removing the offer");
    }

    $stmt->close();
} else {
    // Invalid request
    header("Location: Dashboard.php?error=Invalid request");
}

// Close the database connection
$conn->close();
?>
