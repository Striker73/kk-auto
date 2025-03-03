<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get current user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        // Validate username
        if ($user['username'] !== $_POST['username']) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$_POST['username'], $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                throw new Exception("Това потребителско име вече съществува");
            }
        }

        // Check if password is being updated
        if (!empty($_POST['new_password'])) {
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                throw new Exception("Паролите не съвпадат");
            }
            
            $sql = "UPDATE users SET username = ?, full_name = ?, password = ? WHERE id = ?";
            $params = [
                $_POST['username'],
                $_POST['full_name'],
                password_hash($_POST['new_password'], PASSWORD_DEFAULT),
                $_SESSION['user_id']
            ];
        } else {
            $sql = "UPDATE users SET username = ?, full_name = ? WHERE id = ?";
            $params = [
                $_POST['username'],
                $_POST['full_name'],
                $_SESSION['user_id']
            ];
        }

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($params);

        if ($result) {
            $_SESSION['username'] = $_POST['username'];
            header("Location: settings.php?success=1");
            exit();
        } else {
            throw new Exception("Грешка при запазване на настройките");
        }
    } catch (Exception $e) {
        header("Location: settings.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: settings.php");
exit();
?> 