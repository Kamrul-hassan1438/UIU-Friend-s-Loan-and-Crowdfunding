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
    die("User not logged in.");
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $target_amount = mysqli_real_escape_string($conn, $_POST['target_amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_path = $target_file;
    }


    $query = "INSERT INTO crowdfundings (user_id, title, target_amount, description, deadline, image, collected_amount) 
          VALUES ('$user_id', '$title', '$target_amount', '$description', '$deadline', '$image_path', 0)";

    if (mysqli_query($conn, $query)) {
        header("Location: Dashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
