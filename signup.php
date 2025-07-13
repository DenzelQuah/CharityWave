<?php

define("DB_HOST", "localhost:3307");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "charitywave");

// create connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

// check connection
if ($connection->connect_error) {
    echo "<script>alert('Connection failed: " . $connection->connect_error . "');</script>";
    echo "<script>window.location.href = 'signin.html';</script>"; // Redirect to login page
    exit();
}

// Ensure POST variables are set
if (!isset($_POST['Name'], $_POST['Email'], $_POST['Password'], $_POST['CPassword'])) {
    echo "<script>alert('Form data is missing.');</script>";
    echo "<script>window.location.href = 'signin.html';</script>"; // Redirect to login page
    exit();
}

// Validate email format
$email = $_POST['Email'];
$emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
$domainPart = explode('@', $email)[1] ?? '';
$dotCount = substr_count($domainPart, '.');

if (!preg_match($emailPattern, $email) || $dotCount !== 1) {
    echo "<script>alert('Please enter a valid email address.');</script>";
    echo "<script>window.location.href = 'signup.html';</script>"; // Redirect to login page
    exit();
}

// Check if passwords match
if ($_POST['Password'] !== $_POST['CPassword']) {
    echo "<script>alert('Passwords do not match.');</script>";
    echo "<script>window.location.href = 'signup.html';</script>"; // Redirect to login page
    exit();
}

// Prepare and bind
$stmt = $connection->prepare("INSERT INTO signup (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_POST['Name'], $_POST['Email'], $_POST['Password']);

// Execute the statement
if ($stmt->execute() === TRUE) {
    echo "<script>alert('You have successfully registered!');</script>";
    echo "<script>window.location.href = 'signin.html';</script>"; // Redirect to home page
} else {
    echo "<script>alert('Error: This email has been registered.');</script>";
    echo "<script>window.location.href = 'signup.html';</script>"; // Redirect to login page
}

// Close the connection
$stmt->close();
$connection->close();
?>
