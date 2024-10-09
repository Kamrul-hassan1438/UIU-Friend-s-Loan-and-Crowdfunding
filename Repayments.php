<?php
// Include your database connection
include 'db_connection.php';

// Check if the offer_id is set in the URL
if (isset($_GET['offer_id'])) {
    $offer_id = intval($_GET['offer_id']); // Sanitize input

    // Prepare a statement to delete the loan offer
    $stmt = $conn->prepare("DELETE FROM loanoffers WHERE offer_id = ?");
    $stmt->bind_param("i", $offer_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful deletion
        header("Location: Dashboard.php");
    } else {
        // Failed deletion
        echo "Error: Could not remove the loan offer. Please try again later.";
    }

    // Close the statement
    $stmt->close();
} else {
    // If no offer_id is provided
    echo "Invalid request. Please provide a valid offer ID.";
}

// Close the database connection
$conn->close();
?>
