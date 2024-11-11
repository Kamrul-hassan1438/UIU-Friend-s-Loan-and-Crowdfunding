<?php
// Start the session
session_start();

// Check if the user is logged in (optional, depending on your logic)
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit an offer.";
    exit();
}

// Database connection settings
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = isset($_POST['loan_id']) ? (int)$_POST['loan_id'] : 0;
    $lender_id = $_SESSION['user_id'];
    $amount_offered = 0.00;
    $interest_rate = isset($_POST['interest_rate']) && $_POST['interest_rate'] !== '' ? (float)$_POST['interest_rate'] : 0;
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';
    $installments = isset($_POST['installments']) ? (int)$_POST['installments'] : 0;
    $additional_info = isset($_POST['additional_info']) ? trim($_POST['additional_info']) : '';


    if ($loan_id && $due_date && $installments) {
        $date_format = 'Y-m-d';
        $d = DateTime::createFromFormat($date_format, $due_date);
        if (!$d || $d->format($date_format) !== $due_date) {
            echo "Invalid due date format.";
            exit();
        }
        $sql = "INSERT INTO loanoffers (loan_id, lender_id, amount_offered, interest_rate, due_date, installments, additional_info, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiddsis", $loan_id, $lender_id, $amount_offered, $interest_rate, $due_date, $installments, $additional_info);


        if ($stmt->execute()) {

            header("Location: HomePage.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Please fill all the required fields.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
