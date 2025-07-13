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

// Delete volunteer data if delete button is clicked
if(isset($_POST["delete_volunteer"])){
    // Ensure IC is set
    if(isset($_POST['ic'])){
        $ic = $_POST['ic'];

        // SQL query to delete volunteer data
        $query = "DELETE FROM `registerVolunteer` WHERE `ic` = ?";
        
        try {
            $stmt = $connection->prepare($query);
            $stmt->execute([$ic]);
            
            // Check if any rows were affected by the delete operation
            if($stmt->rowCount() > 0) {
                // Redirect with success message
                header('Location: viewVolunteer.php?delete_msg=Volunteer Data Successfully Deleted.');
                exit;
            } else {
                // No rows affected, display message
                die("No rows affected by the delete operation.");
            }

            $stmt->closeCursor(); // Close the cursor to allow for another query
        } catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    } else {
        die("IC parameter is missing.");
    }
}
?>

<!-- HTML Form for deleting volunteer data -->
<form action="deleteVolunteer.php" method="post">
    <input type="hidden" name="ic" value="<?php echo htmlspecialchars($_GET['ic']); ?>">
    <input type="submit" name="delete_volunteer" value="DELETE" onclick="return confirm('Are you sure you want to delete this volunteer data?');">
</form>
