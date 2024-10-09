<?php
// accept_offer.php

session_start();
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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user

// Get the offer ID from URL
if (isset($_GET['offer_id'])) {
    $offer_id = intval($_GET['offer_id']);

    // Fetch loan offer details
    $sql = "
        SELECT 
            loanoffers.loan_id,
            loanoffers.lender_id,
            loanoffers.interest_rate,
            loanoffers.installments,
            loanoffers.due_date,
            loans.amount AS loan_amount
        FROM 
            loanoffers
        JOIN 
            loans ON loanoffers.loan_id = loans.loan_id
        WHERE 
            loanoffers.offer_id = ?
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $offer = $result->fetch_assoc();

        // Update offer status to 'accepted'
        $update_offer_sql = "UPDATE loanoffers SET status = 'accepted' WHERE offer_id = ?";
        $stmt_update_offer = $conn->prepare($update_offer_sql);
        $stmt_update_offer->bind_param("i", $offer_id);
        
        if (!$stmt_update_offer->execute()) {
            echo "Error updating offer status: " . $stmt_update_offer->error;
            exit();
        }

        // Update loan status to 'approved'
        $update_loan_sql = "UPDATE loans SET status = 'approved' WHERE loan_id = ?";
        $stmt_update_loan = $conn->prepare($update_loan_sql);
        $stmt_update_loan->bind_param("i", $offer['loan_id']);
        
        if (!$stmt_update_loan->execute()) {
            echo "Error updating loan status: " . $stmt_update_loan->error;
            exit();
        }

        // Redirect to the dashboard with a success message
        header("Location: Dashboard.php?offer_accepted=success");
        exit();
    } else {
        echo "No offer found with the specified ID.";
        exit();
    }
} else {
    echo "No offer ID specified.";
    exit();
}

// Close the connection
$conn->close();
?>
