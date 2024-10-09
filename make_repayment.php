<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

if (isset($_POST['repayment_id'])) {
    $repayment_id = intval($_POST['repayment_id']);

    
    $sql = "
        SELECT 
            loan_id, lender_id, repayment_amount, installment_number, next_payment_date, final_due_date
        FROM 
            repayments
        WHERE 
            repayment_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $repayment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $repayment = $result->fetch_assoc();

    if ($repayment) {
        
        $current_date = new DateTime();
        
        
        $due_date = new DateTime($repayment['final_due_date']);
        $total_days = $current_date->diff($due_date)->days;
        
        
        $new_installment_number = max(0, $repayment['installment_number'] - 1);
        
        
        if ($new_installment_number > 0) {
            $days_per_installment = floor($total_days / $new_installment_number);
            $next_payment_date = clone $current_date;
            $next_payment_date->add(new DateInterval('P' . $days_per_installment . 'D'));
        } else {
            
            $next_payment_date = null; 
        }

        
        $update_repayment_sql = "
            UPDATE repayments 
            SET repayment_date = ?, next_payment_date = ?, installment_number = ?
            WHERE repayment_id = ?";
        
        $stmt_update = $conn->prepare($update_repayment_sql);
        $stmt_update->bind_param("ssii", $current_date->format('Y-m-d'), 
            $next_payment_date ? $next_payment_date->format('Y-m-d') : null, 
            $new_installment_number, $repayment_id);
        $stmt_update->execute();

        header("Location: Dashboard.php?repayment_success=1");
        exit();
    }
}

mysqli_close($conn);
?>
