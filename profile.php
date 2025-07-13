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
$row = []; // Initialize row to hold fetched profile data

// Fetch profile data if email is provided in GET parameter
if(isset($_GET['email'])){
    $email = $_GET['email'];
    $query = "SELECT * FROM signup WHERE email = ?";
    
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if profile exists
        if(!$row) {
            die("Profile not found");
        }
        
        $stmt->closeCursor(); // Close the cursor to allow for another query
    } catch(PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    die("Email not provided");
}

// Process form submission to update profile data
if(isset($_POST["update_profile"])){
    // Ensure email is set
    if(isset($_GET['email'])){
        $email = $_GET['email'];
    }

    // Retrieve form data
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $country = $_POST["country"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    
    // SQL query to update profile data
    $query = "UPDATE signup SET 
              name = ?, 
              phone = ?, 
              country = ?, 
              dob = ?, 
              gender = ? 
              WHERE email = ?";
    
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute([$name, $phone, $country, $dob, $gender, $email]);
        
        // Check if any rows were affected by the update operation
        if($stmt->rowCount() > 0) {
            // Redirect with success message
            header('Location: index.html?update_msg=You Have Successfully Updated Your Profile.');
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

<!-- HTML Form for updating profile data -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Details</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<?php if(isset($email) && !empty($row)): ?>
<form action="profile.php?email=<?php echo htmlspecialchars($email); ?>" method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" disabled>

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

    <label for="phone">Phone Number</label>
    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

    <label for="country">Country</label>
    <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($row['country']); ?>" required>

    <label for="dob">Date of Birth</label>
    <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required>

    <label>Gender</label>
    <div class="radio-group">
        <label>
            <input type="radio" name="gender" value="male" <?php echo $row['gender'] == 'male' ? 'checked' : ''; ?> required> Male
        </label>
        <label>
            <input type="radio" name="gender" value="female" <?php echo $row['gender'] == 'female' ? 'checked' : ''; ?> required> Female
        </label>
    </div>

    <input type="submit" name="update_profile" value="UPDATE">
</form>
<?php else: ?>
<p>Profile data not found.</p>
<?php endif; ?>
</body>
</html>