<?php
session_start(); // Start the session to access user info

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
    die("User not logged in.");
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get loan details from the form
    $amount = $conn->real_escape_string($_POST['amount']);
    $expected_return_date = $conn->real_escape_string($_POST['expected_return_date']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = 'pending'; // Default status

    // Prepare the SQL query
    $sql = "INSERT INTO loans (user_id, amount, expected_return_date, description, status) VALUES ('$user_id', '$amount', '$expected_return_date', '$description', '$status')";

    // Handle document upload if provided
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to save the uploaded file
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES['document']['name']);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file size and type
        if ($_FILES['document']['size'] > 5000000) { // Limit file size to 5MB
            die("Sorry, your file is too large.");
        }
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        if (!in_array($fileType, $allowed_types)) {
            die("Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_file)) {
            // Include the document path in the SQL query
            $sql = "INSERT INTO loans (user_id, amount, expected_return_date, description, document, status) VALUES ('$user_id', '$amount', '$expected_return_date', '$description', '$target_file', '$status')";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Loan request submitted successfully!";
        // Redirect to a success page or back to the homepage
        header("Location: HomePage.php");
        exit();
    } else {
        echo "Error submitting loan request: " . $conn->error;
    }
}

$conn->close();
?>
