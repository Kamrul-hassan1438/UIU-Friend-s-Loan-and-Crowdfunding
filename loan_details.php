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

// Get the loan ID from the URL
$loan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch loan details using the loan_id
$sql = "SELECT loans.loan_id, loans.amount, loans.expected_return_date, loans.description, loans.document, users.username, users.uiu_id 
        FROM loans
        JOIN users ON loans.user_id = users.user_id
        WHERE loans.loan_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the loan exists
if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
} else {
    echo "Loan not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loan Details</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .loan-details {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .loan-details img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .loan-details h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .loan-details p {
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
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
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
        <div class="loan-details">
            <h2>Loan Details</h2>
            <img src="<?php echo !empty($loan['document']) ? $loan['document'] : 'img/Intersect.png'; ?>" alt="Loan Document" />
            <p><strong>Created By:</strong> <?php echo htmlspecialchars($loan['username']); ?> (UIU ID: <?php echo htmlspecialchars($loan['uiu_id']); ?>)</p>
            <p><strong>Amount:</strong> <?php echo htmlspecialchars($loan['amount']); ?> Taka</p>
            <p><strong>Expected Return Date:</strong> <?php echo htmlspecialchars($loan['expected_return_date']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($loan['description']); ?></p>

            <button onclick="window.history.back()">Back to Loans</button>
            <br>
            <button onclick="window.location.href='offering.php?loan_id=<?php echo htmlspecialchars($loan['loan_id']); ?>'">Send Offer</button>
        </div>
    </main>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
