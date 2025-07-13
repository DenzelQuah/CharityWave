<?php
session_start();

$host = 'localhost:3307';
$dbname = 'charitywave';
$user = 'root';
$pass = '';

$connection = new mysqli($host, $user, $pass, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL statement to retrieve the user data from the user table
$stmt = $connection->prepare("SELECT Email, Password FROM signup WHERE Email = ?");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dbEmail, $dbPassword);
        $stmt->fetch();

        // Debugging outputs
        echo "<script>console.log('Email entered: " . $email . "');</script>";
        echo "<script>console.log('Password entered: " . $password . "');</script>";
        echo "<script>console.log('Password from database: " . $dbPassword . "');</script>";

        // Compare the plain text password
        if ($password === $dbPassword) {
            // Passwords match, login successful
            $_SESSION['email'] = $dbEmail;
            echo "<script>alert('Login successful!');
                  window.location.href = 'index.html';</script>";
            exit();
        } else {
            // Passwords do not match
            echo "<script>alert('Incorrect password.');
                  window.location.href = 'signin.html';</script>";
            exit();
        }
    } else {
        // No user found with this email
        echo "<script>alert('No user found with this email.');
              window.location.href = 'signup.html';</script>";
        exit();
    }
} else {
    // Error executing SQL statement
    echo "<script>alert('Error: " . $stmt->error . "');
          window.location.href = 'signin.html';</script>";
    exit();
}

$stmt->close();
$connection->close();
?>
