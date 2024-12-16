<?php
session_start();
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_get_info($user['password'])['algo'] == 0) {
            $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
            $updateStmt = $conn->prepare("UPDATE Users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
            $user['password'] = $hashedPassword;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>
