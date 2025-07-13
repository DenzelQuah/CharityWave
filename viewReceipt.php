<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost:3307";
$dbname = 'charitywave';
$user = 'root';
$pass = '';

// Create connection
$connection = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT * FROM makepayment";
$result = $connection->query($sql);

// Initialize variables for donation statistics
$totalDonation = 0;
$maxDonation = 0;
$minDonation = PHP_INT_MAX;
$countDonations = 0;

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donationAmount = floatval($row["donationAmount"]);
            $totalDonation += $donationAmount;
            $countDonations++;
            
            if ($donationAmount > $maxDonation) {
                $maxDonation = $donationAmount;
            }
            
            if ($donationAmount < $minDonation) {
                $minDonation = $donationAmount;
            }
        }
        $averageDonation = $totalDonation / $countDonations;
    } else {
        echo "No results found in makepayment";
        $averageDonation = 0;
    }
} else {
    die("Query failed: " . $connection->error);
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Details</title>
    <link rel="stylesheet" href="viewReceipt.css">
</head>
<body>
    <div class="container">
        <h1 class="header">Receipt Details</h1>

        <ul class="navbar">
            <li><a href="profile.html">Profile</a></li>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="makePayment.html">Donation</a></li>
            <li><a href="GetDonation.html">Beneficiaries</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>

        <div class="statistics">
            <h2>Donation Statistics</h2>
            <ul>
                <li>Total Donations: RM <?php echo number_format($totalDonation, 2); ?></li>
                <li>Average Donation: RM <?php echo number_format($averageDonation, 2); ?></li>
                <li>Maximum Donation: RM <?php echo number_format($maxDonation, 2); ?></li>
                <li>Minimum Donation: RM <?php echo number_format($minDonation, 2); ?></li>
                <li>Number of Donations: <?php echo $countDonations; ?></li>
            </ul>
        </div>

        <hr>

        <div class="receipts">
            <?php
            $connection = new mysqli($host, $user, $pass, $dbname);

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM makepayment";
            $result = $connection->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='receipt'>";
                        echo "<h2>Card Name: " . htmlspecialchars($row["cardName"]) . "</h2>";
                        echo "<div class='receipt-details'>";
                        echo "<p><strong>Card Number:</strong> " . htmlspecialchars($row["cardNum"]) . "</p>";
                        echo "<p><strong>Expire Month:</strong> " . htmlspecialchars($row["expMonth"]) . "</p>";
                        echo "<p><strong>Expire Year:</strong> " . htmlspecialchars($row["expYear"]) . "</p>";
                        echo "<p><strong>CVV:</strong> " . htmlspecialchars($row["cvv"]) . "</p>";
                        echo "<p><strong>Donation Amount:</strong> RM " . htmlspecialchars($row["donationAmount"]) . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "No results found in receipt";
                }
            } else {
                die("Query failed: " . $connection->error);
            }

            $connection->close();
            ?>
        </div>
    </div>
</body>
</html>
