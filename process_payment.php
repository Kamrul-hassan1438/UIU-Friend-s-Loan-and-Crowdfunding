<?php 
// Include database connection file
include 'db_connection.php';

// Check if repayment_id and amount are set in the GET request
if (isset($_GET['repayment_id']) && isset($_GET['amount'])) {
    $repayment_id = $_GET['repayment_id'];
    $amount = $_GET['amount'];

    // Step 1: Fetch the current repayment details
    $query = "SELECT total_amount, paid_amount, loan_offer_id FROM repayments WHERE repayment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $repayment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $repayment = $result->fetch_assoc();
        $total_amount = $repayment['total_amount'];
        $paid_amount = $repayment['paid_amount'];
        $loan_offer_id = $repayment['loan_offer_id'];

        // Step 2: Calculate new paid_amount
        $new_paid_amount = $paid_amount + $amount;

        // Step 3: Update the paid_amount in the repayments table
        $update_query = "UPDATE repayments SET paid_amount = ? WHERE repayment_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("di", $new_paid_amount, $repayment_id);

        if ($update_stmt->execute()) {
            // Step 4: Check if total_amount equals paid_amount
            if ($new_paid_amount >= $total_amount) {
                // Step 5: If so, delete the repayment record
                $delete_query = "DELETE FROM repayments WHERE repayment_id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("i", $repayment_id);
                $delete_stmt->execute();

                // Check if any repayments are left for the associated loan_offer_id
                $check_remaining_query = "SELECT COUNT(*) as remaining_count FROM repayments WHERE loan_offer_id = ?";
                $check_stmt = $conn->prepare($check_remaining_query);
                $check_stmt->bind_param("i", $loan_offer_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $remaining = $check_result->fetch_assoc();

                // If there are no remaining repayments, delete the loan and loan offer
                if ($remaining['remaining_count'] == 0) {
                    // Delete the loan
                    $delete_loan_query = "DELETE FROM loans WHERE loan_id = (SELECT loan_id FROM loanoffers WHERE offer_id = ?)";
                    $delete_loan_stmt = $conn->prepare($delete_loan_query);
                    $delete_loan_stmt->bind_param("i", $loan_offer_id);
                    $delete_loan_stmt->execute();

                    // Delete the loan offer
                    $delete_loan_offer_query = "DELETE FROM loanoffers WHERE offer_id = ?";
                    $delete_loan_offer_stmt = $conn->prepare($delete_loan_offer_query);
                    $delete_loan_offer_stmt->bind_param("i", $loan_offer_id);
                    $delete_loan_offer_stmt->execute();
                }
            }

            // Success message
            echo "Payment of $amount was successful. Updated paid amount is $new_paid_amount.";
        } else {
            echo "Error updating the paid amount.";
        }
    } else {
        echo "No repayment record found.";
    }
} else {
    echo "Invalid request.";
}
?>
