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

// Get the campaign ID from the URL
$campaign_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch campaign details from the database
$query = "SELECT * FROM crowdfundings WHERE crowdfunding_id = $campaign_id";
$result = mysqli_query($conn, $query);

// Check if the campaign exists
if ($result && mysqli_num_rows($result) > 0) {
    $campaign = mysqli_fetch_assoc($result);
} else {
    die("Campaign not found.");
}

// Generate the campaign URL for sharing
$campaign_url = "http://localhost/UIU-Friend-s-Loan-and-Crowdfunding/campaign_details.php?id=$campaign_id"; // Replace 'yourdomain.com' with your actual domain.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($campaign['title']); ?> - Details</title>

    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        #container1 {
            width: 100%;
        }

        #child1 {
            width: 100%;
            padding-top: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #child1 img {
            width: 500px;
            height: 260px;
            padding-right: 30px;
        }

        #child1 p {
            margin-left: 0;
            width: 700px;
            height: 200px;
            font-size: 3.5em;
        }

        #child2 {
            width: 100%;
            padding-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #child2 p {
            width: 80%;
            font-size: 1.2em;
            color: #000;
        }

        #child2 a {
            color: #fff;
        }

        #child3 {
            width: 100%;
            padding-top: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        table {
            width: 40%;
            display: flex;
            justify-content: center;
        }

        tr {
            text-align: center;
            width: 100%;
        }

        td {
            padding: 5px;
        }

        #child3 button {
            width: 150px;
            height: 45px;
            background-color: #fff;
            color: #ff9448;
            border-radius: 15px;
            border: none;
            font-size: 1.6em;
        }

        #child3 button:hover {
            background-color: #000;
            color: #fff;
        }

        /* Share Modal */
        .share-options {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 8px;
        }

        .share-options button {
            background: none;
            border: none;
            margin: 5px;
            cursor: pointer;
        }

        .share-options button img {
            width: 40px;
            height: 40px;
        }
    </style>
</head>

<body>


    <div id="container1">
        <div id="child1">

            <img src="<?php echo !empty($campaign['image']) ? $campaign['image'] : 'img/default-campaign.png'; ?>" alt="Campaign Image" />

            <p><?php echo htmlspecialchars($campaign['title']); ?></p>
        </div>

        <div id="child2">

            <p>
                <?php echo htmlspecialchars($campaign['description']); ?>
                <br /><br />

                <strong>Target Amount:</strong> <?php echo number_format($campaign['target_amount'], 2); ?> Taka
                <br />
                <strong>Collected Amount:</strong> <?php echo number_format($campaign['collected_amount'], 2); ?> Taka
                <br /><br />

                <a href="donorHistory.php?id=<?php echo $campaign_id; ?>">See donor history</a>
            </p>
        </div>

        <div id="child3">
            <table>

                <tr>

                    <td>
                        <button onclick="toggleShareOptions()">Share</button>
                        <div class="share-options" id="shareOptions">

                            <button onclick="shareOnFacebook()">
                                <img src="img/Facebook.png" alt="Facebook">
                            </button>

                            <button onclick="shareOnWhatsApp()">
                                <img src="img/whatsapp_3938041.png" alt="WhatsApp">
                            </button>

                            <button onclick="shareOnTwitter()">
                                <img src="img/x.png" alt="Twitter">
                            </button>

                            <button onclick="alert('Instagram sharing requires posting manually.')">
                                <img src="img/instagram_2504918.png" alt="Instagram">
                            </button>
                        </div>
                    </td>

                    <td><button onclick="window.location.href='donate.php?id=<?php echo $campaign_id; ?>'">Donate</button></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        function toggleShareOptions() {
            var shareOptions = document.getElementById('shareOptions');
            shareOptions.style.display = (shareOptions.style.display === 'block') ? 'none' : 'block';
        }

        function shareOnFacebook() {
            var url = "<?php echo $campaign_url; ?>";
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url), '_blank');
        }

        function shareOnWhatsApp() {
            var url = "<?php echo $campaign_url; ?>";
            window.open('https://api.whatsapp.com/send?text=' + encodeURIComponent(url), '_blank');
        }

        function shareOnTwitter() {
            var url = "<?php echo $campaign_url; ?>";
            window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent("Check out this campaign: " + "<?php echo $campaign['title']; ?>"), '_blank');
        }
    </script>
</body>

</html>