<?php
session_start(); // Make sure the session is started to access user info

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $conn->real_escape_string($_POST['username']);
    $newPhone = $conn->real_escape_string($_POST['phone']);

    $profile_image = null;

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $unique_name = $user_id . "_" . time() . "_" . basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $unique_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        if ($_FILES['profile_image']['size'] > 5000000) {
            echo json_encode(['success' => false, 'error' => 'File is too large. Maximum size is 5MB.']);
            exit();
        }

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.']);
            exit();
        }


        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $profile_image = $target_file;
        } else {
            echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
            exit();
        }
    }


    if ($profile_image) {
        $sql = "UPDATE users SET username = ?, phone = ?, profile_image = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $newName, $newPhone, $profile_image, $user_id);
    } else {
        $sql = "UPDATE users SET username = ?, phone = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $newName, $newPhone, $user_id);
    }

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['username'] = $newName;
        $_SESSION['phone'] = $newPhone;
        if ($profile_image) {
            $_SESSION['profile_image'] = $profile_image;
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error updating profile: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
