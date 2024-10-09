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
    <style>.main-content {
    display: flex;
    justify-content: flex-start; /* Align items to the left */
    align-items: flex-start;
    padding: 20px;
}
.notification-section {
    width: 60%;
    background-color: rgba(255, 255, 255, 0.5);;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    margin-left: 20px; /* Space between notification and profile */
    height: 400px; /* Set a fixed height */
    overflow-y: auto; /* Enable vertical scroll */
}
.profile-section, .notification-section {
    background-color:rgba(255, 255, 255, 0.5);;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-section {
    width: 30%;
    text-align: center;
}

.profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile-pic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-bottom: 20px;
}

.profile-card h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.profile-card p {
    margin-bottom: 8px;
    font-size: 16px;
}

.profile-btn {
    background-color: #FF914D;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
}

.notification-section {

    width: 60%;
}

.notification-section h2 {
    font-size: 22px;
    margin-bottom: 20px;
}

.notifications .notification {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #FDD5B1;
    border-radius: 8px;
}

.notifications .notification span {
    font-size: 16px;
}

.notifications .btn {
    background-color: #FF914D;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.action-buttons {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.action-btn {
    background-color: #FF914D;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    margin: 0 15px;
    cursor: pointer;
    font-size: 16px;
}

.action-btn:hover {
    background-color: #FF6A00;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}</style>
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
                <h2>Terms and Conditions </h2>
                <div class="notifications">
                    <div class="notification">
                        <span>1 .The borrower must clearly state the amount requested and the purpose of the loan. The lender reserves the right to approve or deny the loan based on the provided information</span>
                    </div>
                    <div class="notification">
                        <span>2 .An agreed-upon interest rate will apply to the loan amount. This interest rate should be specified in writing, 
                            either as a fixed or variable rate, and will determine the total repayment amount.</span>
                    </div>
                    <div class="notification">
                        <span>3 .The borrower agrees to repay the loan in regular installments (e.g., monthly, quarterly) as per the agreed schedule. The exact repayment period and due dates should be clearly outlined in the loan agreement.</span>
                    </div>
                    <div class="notification">
                        <span>4 .If applicable, the borrower will provide collateral to secure the loan. The lender will have the right to seize the collateral in case of default. The terms for collateral valuation and possession should be stated clearly.</span>
                    </div>
                    <div class="notification">
                        <span>5. If any of the brrower or giver fund to be chited the autority can give punishment </span>
                    </div>
                    <div class="notification">
                        <span>6 . Can't use the money of crowedfunding as your personal expanse</span>
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
