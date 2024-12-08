<?php
include 'db.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Add, Edit, Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password =$_POST['password']; 
    $gender = $_POST['gender'];

    if ($action == 'edit') {
        // Update record
        $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', password='$password', gender='$gender' WHERE id=$id";
        $conn->query($sql);
        header("Location: index.php"); // Redirect after update
    } else {
        // Add new record
        $sql = "INSERT INTO users (firstname, lastname, email, password, gender) VALUES ('$firstname', '$lastname', '$email', '$password', '$gender')";
        $conn->query($sql);
        header("Location: index.php"); // Redirect after insert
    }
}

// Handle Delete
if ($action == 'delete' && $id) {
    $sql = "DELETE FROM users WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php"); // Redirect after delete
}


// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CRUD Application</title>
</head>
<body>

<h2>CRUD Application</h2>

<!-- Form for Add/Edit -->
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $action == 'edit' ? $id : ''; ?>">

    <label for="firstname">First Name:</label>
    <input type="text" name="firstname" id="firstname" required><br>

    <label for="lastname">Last Name:</label>
    <input type="text" name="lastname" id="lastname" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select><br>

    <button type="submit"><?php echo $action == 'edit' ? 'Update' : 'Add'; ?> User</button>
    <button type="reset">Reset</button>
</form>

<h3>Users List</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td>
                <a href="index.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                <a href="index.php?action=delete&id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
