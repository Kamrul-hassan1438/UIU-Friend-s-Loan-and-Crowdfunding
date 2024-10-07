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
                <a href="HomePage.php" style="color: azure;">Home</a>
                <a href="Donationpage-2.php">Crowdfunding</a>
                <a href="Reviews.html">Reviews</a>
                <a href="Dashboard.php">Dashboard</a>
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
            <div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Update Profile</h2>
        <form id="updateProfileForm" method="post" enctype="multipart/form-data">
            <label for="profilePic">Profile Picture:</label>
            <input type="file" id="profilePic" name="profilePic" accept="image/*"><br><br>

            <label for="nameInput">Name:</label>
            <input type="text" id="nameInput" name="name" value="<?= $username ?>"><br><br>

            <label for="phoneInput">Phone Number:</label>
            <input type="text" id="phoneInput" name="phone" value="<?= $phone ?>"><br><br>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

            <!-- Notification Section -->
            <div class="notification-section">
                <h2>Notifications</h2>
                <div class="notifications">
                    <div class="notification">
                        <span>🔔 Sabrina Afrin requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Kamrul Hassan started a fundraising</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Maaz Bin Hossain reviewed your request</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Sabit Molla requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Sabit Molla requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Sabit Molla requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Sabit Molla requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                    <div class="notification">
                        <span>🔔 Sabit Molla requested for 6000 BDT</span>
                        <button class="btn">Action</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
    <form action="Request_for_Loan.html" method="GET">
        <button class="action-btn" type="submit">Request for loan</button>
    </form>

    <form action="Fundraising.html" method="GET">
        <button class="action-btn" type="submit">Start crowdfunding</button>
    </form>

    <form action="loans.php" method="GET">
        <button class="action-btn" type="submit">Loan Requests</button>
    </form>
</div>
    </div>
    <script src="js/Homepage.js"></script>
</body>
</html>
