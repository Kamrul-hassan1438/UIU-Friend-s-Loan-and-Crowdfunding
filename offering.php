<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demand Your Offer</title>
    <link rel="icon" href="img/social-support.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/offring.css" />
</head>

<body>
    <div class="container">
        <header>
            <div>
                <img src="img/Brand Logo 1.png" alt="" id="logo" />
            </div>
            <nav>
                <a href="HomePage.php">Home</a>
                <a href="Donation.html">Crowdfunding</a>
                <a href="Reviews.html">Reviews</a>
                <a href="Dashboard.php">Dashboard</a>
                <a href="login.html">Logout</a>
            </nav>
        </header>

        <div class="form-container">
            <h1>Submit your offer</h1>
            <form action="submit_offer.php" method="POST">
                <input type="hidden" name="loan_id" value="<?php echo !empty($_GET['loan_id']) ? htmlspecialchars($_GET['loan_id']) : 0; ?>" />

                <label for="return-date">Expected Return Date</label>
                <input type="date" id="return-date" name="due_date" required>

                <label for="interest">Percentage of Interest</label>
                <input type="number" id="interest" name="interest_rate" placeholder="Percentage of interest" step="0.01">

                <label for="installment">Number of Installments</label>
                <input type="number" id="installment" name="installments" placeholder="Number of installments" required>

                <label for="others">Others</label>
                <textarea id="others" name="additional_info" rows="4" placeholder="Additional information"></textarea>

                <button type="submit">Done</button>
                
            </form>
        </div>
    </div>
</body>

</html>
