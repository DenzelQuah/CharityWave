<?php

define("DB_HOST", "localhost:3307");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "charitywave");


// create connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

// check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//CREATE
$sql = "INSERT INTO beneficiaries (full_name, email, phone_number, address, monthly_income,bank_acc,type, purpose)
VALUES ('".$_POST['full_name']."',
        '".$_POST['email']."',
        '".$_POST['phone_number']."',
        '".$_POST['address']."',
        '".$_POST['monthly_income']."',
        '".$_POST['bank_acc']."',
        '".$_POST['type']."',
        '".$_POST['purpose']."')";

if ($connection->query($sql) == TRUE) { 
    echo "Your record is now created";
} else {
    echo "Error: " . $sql . "<br>" . $connection->error; 
}

$connection->close();
?>