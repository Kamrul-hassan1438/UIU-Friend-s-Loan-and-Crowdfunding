<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

// Fetch user information from the session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$uiu_id = $_SESSION['uiu_id'];
$phone = $_SESSION['phone'];
$profile_image = $_SESSION['profile_image'] ?: 'img/default-profile.png';  // Use default image if none is uploaded
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/HomePage.css">
</head>

<body>
    <div class="container">
        <header>
            <div>
                <img src="img/Brand Logo 1.png" alt="Brand Logo" id="logo" />
            </div>
            <nav>
                <a href="HomePage.html" style="color: azure;">Home</a>
                <a href="Donation.html">Crowdfunding</a>
                <a href="Reviews.html">Reviews</a>
                <a href="Dashboard.html">Dashboard</a>
                <a href="login.html">Logout</a>
            </nav>
        </header>

        <div class="main-content">
            <div class="profile-section">
                <div class="profile-card">
                    <img src="<?= $profile_image ?>" alt="Profile Picture" class="profile-pic">
                    <h2 id="name"><?= $username ?></h2>
                    <p id="UIU-ID">ID: <?= $uiu_id ?></p>
                    <p id="Phone-number">Phone Number: <?= $phone ?></p>
                    <p>Email: <?= $email ?></p>
                    <button class="profile-btn">Update Profile</button>
                </div>
            </div>

            <!-- Notification and other sections remain unchanged -->
        </div>
    </div>
    <script src="js\Homepage.js"></script>
</body>
</html>
