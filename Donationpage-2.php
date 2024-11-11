<?php
// Start the session to access user info
session_start();

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

// SQL query to fetch crowdfunding campaigns
$query = "SELECT crowdfunding_id, title, description, image FROM crowdfundings";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Donation</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .other-campaigns {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }

        .campaign-card {
            width: 22%;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: background-color 0.3s ease;
            overflow: hidden;
        }

        .campaign-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            display: block;
        }

        .campaign-card p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .campaign-card:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <header>
        <div>
            <img src="img/Brand Logo 1.png" alt="Brand Logo" id="logo" />
        </div>
        <nav>
            <a href="HomePage.php">Home</a>
            <a href="Donation.html" style="color: azure;">Crowdfunding</a>
            <a href="Dashboard.php">Dashboard</a>
            <a href="login.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="other-campaigns">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="campaign-card">';
                    echo '<img src="' . (!empty($row['image']) ? $row['image'] : 'img/Intersect.png') . '" alt="Campaign Image" />';
                    echo '<p>' . htmlspecialchars($row['title']) . '</p>'; // Title of the campaign
                    echo '<button onclick="window.location.href=\'click_details _crowdfunding.php?id=' . $row['crowdfunding_id'] . '\'">Read more</button>';
                    echo '</div>';
                }
            } else {
                echo "<p>No campaigns available at the moment.</p>";
            }
            ?>
        </div>
    </main>
</body>

</html>