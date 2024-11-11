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


$logged_in_user_id = $_SESSION['user_id'];


$query = "SELECT loans.loan_id, loans.amount, loans.document, loans.expected_return_date, users.username, users.uiu_id 
          FROM loans
          JOIN users ON loans.user_id = users.user_id
          WHERE loans.status = 'pending' AND loans.user_id != ?";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loans</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .loans {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }

        .loan-card {
            width: 22%;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: background-color 0.3s ease;
            overflow: hidden;
        }

        .loan-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            display: block;
        }

        .loan-card p {
            font-size: 16px;
            margin-bottom: 10px;
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

        .loan-card:hover {
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
            <a href="Donation.html">Crowdfunding</a>
            <a href="Reviews.html">Reviews</a>
            <a href="Dashboard.html">Dashboard</a>
            <a href="login.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="loans">
            <?php
            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="loan-card">';
                    echo '<img src="' . (!empty($row['document']) ? $row['document'] : 'img/Intersect.png') . '" alt="Loan Document" />';
                    echo '<p>' . htmlspecialchars($row['username']) . '</p>';
                    echo '<p>' . htmlspecialchars($row['uiu_id']) . '</p>';
                    echo '<p>' . htmlspecialchars($row['amount']) . ' Taka</p>';
                    echo '<button onclick="window.location.href=\'loan_details.php?id=' . $row['loan_id'] . '\'">See Details</button>';
                    echo '</div>';
                }
            } else {
                echo "<p>No loan requests available.</p>";
            }
            ?>
        </div>
    </main>
</body>

</html>