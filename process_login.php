<?php
session_start();
require_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        // Debug log
        error_log("Login attempt for username: " . $username);
        
        $stmt = $pdo->prepare("SELECT id, username, role, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user) {
            error_log("User found, verifying password");
            if (password_verify($password, $user['password'])) {
                error_log("Password verified successfully");
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['client_id'] = $user['client_id'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                error_log("Password verification failed");
                header("Location: login.php?error=invalid_password");
                exit();
            }
        } else {
            error_log("User not found");
            header("Location: login.php?error=invalid_user");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: login.php?error=system");
        exit();
    }
}

// If we get here, redirect to login
header("Location: login.php");
exit();
?>