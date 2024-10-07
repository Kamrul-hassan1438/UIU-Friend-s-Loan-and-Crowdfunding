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

    // Prepare the SQL statement to update the offer status
    $sql = "UPDATE loanoffers SET status = 'accepted' WHERE offer_id = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    
    if ($stmt->execute()) {
        // Offer status updated successfully
        header("Location: Dashboard.php?message=Offer accepted successfully");
        exit(); // Stop further execution
    } else {
        // Error updating the offer status
        header("Location: Dashboard.php?error=Error accepting the offer");
        exit(); // Stop further execution
    }

    $stmt->close();
} else {
    // Invalid request
    header("Location: Dashboard.php?error=Invalid request");
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();
?>
