<?php
session_start();
require 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $password, $role);

    if ($stmt->execute()) {
        echo "User Added Successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/users.css">
    <script src="../script/users.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>New User</title>
</head>
<body>
    <header>
        <?php
            include '../php/header.php';
        ?>
    </header>
    <div class="user-container">
        <div class="user-header">
            <h1>Create a New User</h1>
            <p>Fill in the form below to add a new user to the system.</p>
        </div>
        <form id="users-form" class="user-form">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="member">Member</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="button" class="submit-btn" onclick="submitUserForm()">Save</button>
        </form>
        <div id="userText" class="popup">
            <p id="userResult"></p>
        </div>
    </div>
</body>
</html>
