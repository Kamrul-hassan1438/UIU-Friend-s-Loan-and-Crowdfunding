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

// Validate and get form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = isset($_POST['loan_id']) ? (int)$_POST['loan_id'] : 0;
    $lender_id = $_SESSION['user_id']; // Assuming the user ID is stored in session after login
    $amount_offered = 0.00; // This could be added to the form if necessary
    $interest_rate = isset($_POST['interest_rate']) ? (float)$_POST['interest_rate'] : 0;
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';
    $installments = isset($_POST['installments']) ? (int)$_POST['installments'] : 0;
    $additional_info = isset($_POST['additional_info']) ? trim($_POST['additional_info']) : '';

    // Check if required fields are filled
    if ($loan_id && $interest_rate && $due_date && $installments) {
        // Validate due date format
        $date_format = 'Y-m-d';
        $d = DateTime::createFromFormat($date_format, $due_date);
        if (!$d || $d->format($date_format) !== $due_date) {
            echo "Invalid due date format.";
            exit();
        }

        // Prepare the SQL query to insert the offer into the database
        $sql = "INSERT INTO loanoffers (loan_id, lender_id, amount_offered, interest_rate, due_date, installments, additional_info, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiddsis", $loan_id, $lender_id, $amount_offered, $interest_rate, $due_date, $installments, $additional_info);

        // Execute the query
        if ($stmt->execute()) {
            echo "Your offer has been submitted successfully.";
            // Optionally, redirect the user to another page
            header("Location: HomePage.php"); // Replace with your actual redirection page
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
?>
