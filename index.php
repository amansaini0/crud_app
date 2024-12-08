<?php
include 'db.php';

// Initialize variables
$edit_state = false;
$firstname = $lastname = $email = $gender = '';
$id = 0;

// Handle form submission for both Add and Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    
    if (isset($_POST['id']) && $_POST['id']) {
        // Update existing record
        $id = $_POST['id'];
        $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', gender='$gender' WHERE id=$id";
        if ($conn->query($sql)) {
            echo "User updated successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Add new record
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (firstname, lastname, email, password, gender) 
                VALUES ('$firstname', '$lastname', '$email', '$password', '$gender')";
        if ($conn->query($sql)) {
            echo "User added successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    header('Location: index.php');
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header('Location: index.php');
}

// Handle edit action
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_state = true;
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $gender = $row['gender'];
    }
}
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
    <h1>CRUD Application</h1>
    
    <!-- Form for Adding and Editing Users -->
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?= $edit_state ? $id : '' ?>">
        
        <label>First Name:</label>
        <input type="text" name="firstname" value="<?= $firstname ?>" required><br>
        
        <label>Last Name:</label>
        <input type="text" name="lastname" value="<?= $lastname ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $email ?>" required><br>
        
        <?php if (!$edit_state): ?>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <?php endif; ?>
        
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
        </select><br>
        
        <button type="submit"><?= $edit_state ? 'Update' : 'Submit' ?></button>
        <button type="reset">Reset</button>
    </form>

    <hr>

    <!-- Display All Users -->
    <h2>Users List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['firstname'] ?></td>
                <td><?= $row['lastname'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['gender'] ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
