<?php
$host = "localhost:3307";
$dbname = 'charitywave';
$user = 'root';
$pass = '';

$connection = mysqli_connect($host, $user, $pass, $dbname);

if (mysqli_connect_errno()) {
    echo "Connection Failed: " . mysqli_connect_error();
    exit();
}

$sql = "INSERT INTO makepayment (cardName, cardNum, expMonth, expYear, cvv, donationAmount) VALUES ('".$_POST['cardName']."', '".$_POST['cardNum']."', '".$_POST['expMonth']."', '".$_POST['expYear']."', '".$_POST['cvv']."', '".$_POST['donationAmount']."')";

if ($connection->query($sql) === TRUE) {
    echo "New record created successfully";

    header("Location: viewReceipt.php");
} else {
    echo "Error: " . $sql . "<br>" . $connection->error;
}

$connection->close();
?>
