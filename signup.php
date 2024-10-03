<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "uiu-friends-loan-and-crowdfunding"; 
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $conn->real_escape_string($_POST['password']);
    $uiu_id = $conn->real_escape_string($_POST['uiu_id']);
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        
        $target_dir = "uploads/";
        
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES['profile_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES['profile_image']['size'] > 5000000) {
            die("Sorry, your file is too large.");
        }

        // Allow only certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Successfully uploaded the file
            $profile_image = $target_file;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        $profile_image = NULL; // No image uploaded
    }

    // Insert the data into the database
    $sql = "INSERT INTO Users (username, email, password_hash, uiu_id, profile_image)
            VALUES ('$username', '$email', '$password_hash', '$uiu_id', '$profile_image')";

    // Check if the query was successful
    if ($conn->query($sql) === TRUE) {
        echo "Signup successful! You can now <a href='login.html'>login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
