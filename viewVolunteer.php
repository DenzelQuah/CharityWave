<?php
$host = "localhost:3307";
$dbname = 'charitywave';
$user = 'root';
$pass = '';

// Create a connection
$connection = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Query to select all from registerVolunteer
$query = "SELECT * FROM registerVolunteer";
$result = $connection->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Volunteers</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="box1">
            <h2>All Volunteers</h2>
            <a href="becomevolunteer.php" class="btn btn-primary">Add Volunteer</a>
        </div>
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>IC NUMBER</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Availability</th>
                    <th>Additional Info</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ic']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                            <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['availability']); ?></td>
                            <td><?php echo htmlspecialchars($row['additionalInfo']); ?></td>
                            <td><a href="updateVolunteer.php?ic=<?php echo $row['ic']; ?>" class="btn btn-success">Update</a></td>
                            <td><a href="deleteVolunteer.php?ic=<?php echo $row['ic']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this volunteer?')">Delete</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9">No volunteers found</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS (optional, for certain Bootstrap features) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+pw0s1K56jz3u0sA0e7dEHBDh5L/RYn1O9" crossorigin="anonymous"></script>
</body>
</html>
