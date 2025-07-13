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

// Initialize variables
$row = []; // Initialize row to hold fetched volunteer data

// Fetch volunteer data if IC is provided in GET parameter
if(isset($_GET['ic'])){
    $ic = $_GET['ic'];
    $query = "SELECT * FROM `registerVolunteer` WHERE ic = ?";
    
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute([$ic]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if volunteer exists
        if(!$row) {
            die("Volunteer not found");
        }
        
        $stmt->closeCursor(); // Close the cursor to allow for another query
    } catch(PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    die("IC not provided");
}

// Process form submission to update volunteer data
if(isset($_POST["update_volunteer"])){
    // Ensure IC is set
    if(isset($_GET['ic'])){
        $ic = $_GET['ic'];
    }

    // Retrieve form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $availability = $_POST["availability"];
    $additionalInfo = $_POST["additionalInfo"];
    
    // SQL query to update volunteer data
    $query = "UPDATE `registerVolunteer` SET 
              `firstName` = ?, 
              `lastName` = ?, 
              `phone` = ?, 
              `address` = ?, 
              `availability` = ?, 
              `additionalInfo` = ? 
              WHERE `ic` = ?";
    
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute([$firstName, $lastName, $phone, $address, $availability, $additionalInfo, $ic]);
        
        // Check if any rows were affected by the update operation
        if($stmt->rowCount() > 0) {
            // Redirect with success message
            header('Location: viewVolunteer.php?update_msg=You Have Successfully Updated The Data.');
            exit;
        } else {
            // No rows affected, display message
            die("No rows affected by the update operation.");
        }

        $stmt->closeCursor(); // Close the cursor to allow for another query
    } catch(PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}
?>

<!-- HTML Form for updating volunteer data -->
<?php if(isset($ic) && !empty($row)): ?>
<form action="updateVolunteer.php?ic=<?php echo htmlspecialchars($ic); ?>" method="post">
    <label for="ic">IC NUMBER</label>
    <input type="number" name="ic" id="ic" value="<?php echo htmlspecialchars($ic); ?>" disabled>

    <label for="firstName">First Name</label>
    <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($row['firstName']); ?>" required>

    <label for="lastName">Last Name</label>
    <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($row['lastName']); ?>" required>

    <label for="phone">Telephone Number</label>
    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

    <label for="address">Home Address</label>
    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>

    <label for="availability">Availability</label>
    <select id="availability" name="availability" required>
        <option value="" disabled>Availability</option>
        <option value="weekday_mornings" <?php echo $row['availability'] == 'weekday_mornings' ? 'selected' : ''; ?>>Weekday mornings</option>
        <option value="weekday_afternoons" <?php echo $row['availability'] == 'weekday_afternoons' ? 'selected' : ''; ?>>Weekday afternoons</option>
        <option value="weekends" <?php echo $row['availability'] == 'weekends' ? 'selected' : ''; ?>>Weekends</option>
    </select>

    <label for="additionalInfo">Additional Info</label>
    <input type="text" name="additionalInfo" id="additionalInfo" value="<?php echo htmlspecialchars($row['additionalInfo']); ?>">

    <input type="submit" name="update_volunteer" value="UPDATE">
</form>
<?php else: ?>
<p>Volunteer data not found.</p>
<?php endif; ?>
