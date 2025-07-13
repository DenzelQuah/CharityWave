<?php
$host = 'localhost:3307';
$dbname = 'charitywave';
$user = 'root';
$pass = '';

// Database connection
try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_volunteer'])) {
    // Retrieve and sanitize form data
    $ic = $_POST['ic'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $availability = $_POST['availability'] ?? '';
    $additionalInfo = $_POST['additionalInfo'] ?? '';

    // Ensure required fields are not empty
    if (!empty($ic) && !empty($firstName) && !empty($lastName) && !empty($phone) && !empty($address) && !empty($availability)) {
        // Prepare the SQL statement
        $sql = "INSERT INTO registerVolunteer (ic, firstName, lastName, phone, address, availability, additionalInfo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->execute([$ic, $firstName, $lastName, $phone, $address, $availability, $additionalInfo]);

            // Redirect or display a success message
            header("Location: viewVolunteer.php?success_msg=Registration successful");
            exit;
        } catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    } else {
        // Handle validation error
        die("All required fields must be filled out");
    }
}
?>
