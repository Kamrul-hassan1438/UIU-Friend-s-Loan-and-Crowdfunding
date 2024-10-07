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

// SQL query to fetch loan data and corresponding user information
$query = "SELECT loans.loan_id, loans.amount, loans.document, loans.expected_return_date, users.username, users.uiu_id
          FROM loans
          JOIN users ON loans.user_id = users.user_id
          WHERE loans.status = 'pending'"; // Fetching only pending loans as an example

$result = mysqli_query($conn, $query);

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
            background-color: rgba(255, 255, 255, 0.2); /* Transparent background */
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: background-color 0.3s ease;
            overflow: hidden;
        }

        .loan-card img {
            width: 100%; /* Ensures the image width fills the card */
            height: 200px; /* Fixed height to enforce uniformity */
            object-fit: cover; /* Ensures image covers the area and maintains aspect ratio */
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

        /* Hover effect for loan card */
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
            <a href="Donation.html" >Crowdfunding</a>
            <a href="Reviews.html">Reviews</a>
            <a href="Dashboard.html">Dashboard</a>
            <a href="login.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="loans">
            <?php
            if (mysqli_num_rows($result) > 0) {
                // Loop through each loan and display in a loan card
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="loan-card">';
                    echo '<img src="' . (!empty($row['document']) ? $row['document'] : 'img/Intersect.png') . '" alt="Loan Document" />';
                    echo '<p>' . htmlspecialchars($row['username']) . '</p>'; // User's name
                    echo '<p>' . htmlspecialchars($row['uiu_id']) . '</p>'; // UIU ID
                    echo '<p>' . htmlspecialchars($row['amount']) . ' Taka</p>'; // Loan amount
                    echo '<button>loan</button>';
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
