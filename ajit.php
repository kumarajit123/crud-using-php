<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
</head>

<body>

    <h2>Employee Management System</h2>

    <?php
// Database connection parameters
$host = 'localhost';
$dbname = 'demo';
$username = 'root';
$password = 'ajit';

// Connect to the database
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Employee
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $stmt = $db->prepare("INSERT INTO employees (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);
        echo "<p>Employee added successfully!</p>";
    }
    // Update Employee
    elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $stmt = $db->prepare("UPDATE employees SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $id]);
        echo "<p>Employee updated successfully!</p>";
    }
    // Delete Employee
    elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        echo "<p>Employee deleted successfully!</p>";
    }
}

// Fetch all employees
$stmt = $db->query("SELECT * FROM employees");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h3>Add Employee</h3>
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <button type="submit" name="add">Add Employee</button>
    </form>

    <h3>Employee List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?php echo $employee['id']; ?></td>
            <td><?php echo $employee['name']; ?></td>
            <td><?php echo $employee['email']; ?></td>
            <td>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
                    <input type="text" name="name" value="<?php echo $employee['name']; ?>">
                    <input type="email" name="email" value="<?php echo $employee['email']; ?>">
                    <button type="submit" name="update">Update</button>
                    <button type="submit" name="delete"
                        onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>