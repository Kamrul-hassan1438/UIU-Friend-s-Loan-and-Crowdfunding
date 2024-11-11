<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiu-friends-loan-and-crowdfunding";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch crowdfunding details
$sql = "SELECT * FROM crowdfundings WHERE crowdfunding_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $campaign_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Invalid crowdfunding campaign or unauthorized access.";
    exit();
}

$campaign = $result->fetch_assoc();

// Handle form submission to update campaign details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target_amount'])) {
    $target_amount = isset($_POST['target_amount']) ? (float)$_POST['target_amount'] : $campaign['target_amount'];
    $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : $campaign['deadline'];

    // Update crowdfunding details
    $update_sql = "UPDATE crowdfundings SET target_amount = ?, deadline = ? WHERE crowdfunding_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsi", $target_amount, $deadline, $campaign_id);

    if ($update_stmt->execute()) {
        // Reload the updated data
        header("Location: details_for_creator.php?id=$campaign_id");
        exit();
    } else {
        echo "Error updating campaign details.";
    }
}

// End the crowdfunding campaign and delete it from the database
if (isset($_POST['end_campaign'])) {
    // First, delete all contributions related to this campaign
    $delete_contributions_sql = "DELETE FROM contributions WHERE crowdfunding_id = ?";
    $delete_contributions_stmt = $conn->prepare($delete_contributions_sql);
    $delete_contributions_stmt->bind_param("i", $campaign_id);
    $delete_contributions_stmt->execute();

    // Now delete the crowdfunding campaign
    $delete_sql = "DELETE FROM crowdfundings WHERE crowdfunding_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $campaign_id, $user_id);

    if ($delete_stmt->execute()) {
        // Redirect to the dashboard after successful deletion
        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error ending the campaign.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crowdfunding Campaign Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #FF6F3C;
        }

        p {
            line-height: 1.5;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="number"],
        input[type="date"],
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        button {
            background-color: #FF6F3C;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #FF5722;
        }

        img {
            width: 300px;
            /* Set a fixed width */
            height: 200px;
            /* Set a fixed height */
            object-fit: cover;
            /* Ensure the image covers the area without distortion */
            border-radius: 8px;
            /* Keep rounded corners */
        }

        a {
            text-decoration: none;
            color: #FF6F3C;
            display: inline-block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Campaign Details</h2>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($campaign['title']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($campaign['description']); ?></p>
        <p><strong>Target Amount:</strong> <?php echo htmlspecialchars($campaign['target_amount']); ?> Taka</p>
        <p><strong>Collected Amount:</strong> <?php echo htmlspecialchars($campaign['collected_amount']); ?> Taka</p>
        <p><strong>Deadline:</strong> <?php echo htmlspecialchars($campaign['deadline']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($campaign['created_at']); ?></p>
        <img src="<?php echo htmlspecialchars($campaign['image']); ?>" alt="Campaign Image" style="max-width: 300px;">

        <form method="POST">
            <h3>Update Campaign Details</h3>
            <label for="target_amount">Target Amount (Taka):</label>
            <input type="number" step="0.01" name="target_amount" value="<?php echo htmlspecialchars($campaign['target_amount']); ?>" required>
            <br>
            <label for="deadline">Deadline:</label>
            <input type="date" name="deadline" value="<?php echo htmlspecialchars($campaign['deadline']); ?>" required>
            <br>
            <button type="submit">Update Details</button>
        </form>

        <form method="POST">
            <button type="submit" name="end_campaign" onclick="return confirm('Are you sure you want to end this campaign and withdraw the funds?');">End Campaign</button>
        </form>

        <a href="Dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>

<?php
$conn->close();
?>