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


if (isset($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];

    
    $delete_offers_stmt = $conn->prepare("DELETE FROM loanoffers WHERE loan_id = ?");
    $delete_offers_stmt->bind_param("i", $loan_id);
    $delete_offers_stmt->execute();
    $delete_offers_stmt->close();


    $stmt = $conn->prepare("DELETE FROM loans WHERE loan_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $loan_id, $_SESSION['user_id']); 

    if ($stmt->execute()) {
        header("Location: Dashboard.php?msg=Loan removed successfully.");
        exit();
    } else {
        header("Location: Dashboard.php?msg=Error removing loan.");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>