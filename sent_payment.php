<?php
// Include database connection file
include 'db_connection.php';

// Check if offer_id is set in the GET request
if (isset($_GET['offer_id'])) {
    $offer_id = $_GET['offer_id'];

    // Step 1: Get the loan_id, loan amount, and interest rate associated with this offer
    $query = "SELECT l.loan_id, l.amount, lo.interest_rate, lo.installments, lo.due_date, lo.created_at 
              FROM loans l
              JOIN loanoffers lo ON l.loan_id = lo.loan_id
              WHERE lo.offer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
        $loan_amount = $loan['amount'];
        $loan_id = $loan['loan_id'];
        $interest_rate = $loan['interest_rate'];
        $installments = $loan['installments'];
        $created_at = $loan['created_at'];

        // Step 2: Ensure amount_offered is equal to the loan amount
        $update_query = "UPDATE loanoffers SET amount_offered = ? WHERE offer_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("di", $loan_amount, $offer_id);

        if ($update_stmt->execute()) {
            // Step 3: Also update the amount in the loans table to match (if needed)
            $update_loan_query = "UPDATE loans SET amount = ? WHERE loan_id = ?";
            $update_loan_stmt = $conn->prepare($update_loan_query);
            $update_loan_stmt->bind_param("di", $loan_amount, $loan_id);

            if ($update_loan_stmt->execute()) {
                // Success message
                header("Location: Dashboard.php");
            } else {
                echo "Error updating the loan amount.";
            }
        } else {
            // Handle update failure
            echo "Error updating the amount offered.";
        }
    } else {
        echo "Loan not found for this offer.";
    }
} else {
    echo "Invalid request.";
}
?>
