<?php
session_start(); // Assuming the user session is already started

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

// Ensure the necessary data is available
$crowdfunding_id = isset($_POST['crowdfunding_id']) ? intval($_POST['crowdfunding_id']) : 0;
$contributor_id = isset($_POST['contributor_id']) ? intval($_POST['contributor_id']) : 0;
$contribution_amount = isset($_POST['contribution_amount']) ? floatval($_POST['contribution_amount']) : 0.00;

if ($crowdfunding_id > 0 && $contributor_id > 0 && $contribution_amount > 0) {
    // Insert contribution into the `contributions` table
    $sql = "INSERT INTO contributions (crowdfunding_id, contributor_id, contribution_amount) 
            VALUES ($crowdfunding_id, $contributor_id, $contribution_amount)";

    if ($conn->query($sql) === TRUE) {
        // Update the `collected_amount` in the `crowdfundings` table
        $update_sql = "UPDATE crowdfundings 
                       SET collected_amount = collected_amount + $contribution_amount 
                       WHERE crowdfunding_id = $crowdfunding_id";

        if ($conn->query($update_sql) === TRUE) {
            echo "Donation successful. Thank you for your contribution!";
            echo "<a href='details_for_creator.php?id=$crowdfunding_id'>Back to Campaign Details</a>";
        } else {
            echo "Error updating collected amount: " . $conn->error;
        }
    } else {
        echo "Error recording contribution: " . $conn->error;
    }
} else {
    echo "Invalid data. Please try again.";
}

$conn->close();
?>
