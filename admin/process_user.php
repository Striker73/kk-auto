<?php
session_start();
require_once '../config/database.php';
require_once '../includes/admin_check.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['action'] === 'add_user') {
            // Validate input
            if (empty($_POST['username']) || empty($_POST['password']) || 
                empty($_POST['full_name']) || empty($_POST['role'])) {
                throw new Exception("Всички полета са задължителни");
            }

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            if ($stmt->fetch()) {
                throw new Exception("Това потребителско име вече съществува");
            }

            // Hash password
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, role) 
                                 VALUES (?, ?, ?, ?)");
            
            $result = $stmt->execute([
                $_POST['username'],
                $hashed_password,
                $_POST['full_name'],
                $_POST['role']
            ]);

            if (!$result) {
                throw new Exception("Грешка при създаване на потребител");
            }

            header("Location: index.php?success=1");
            exit();
        }
        elseif ($_POST['action'] === 'edit_user') {
            // Get existing user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_POST['user_id']]);
            $existingUser = $stmt->fetch();

            if (!$existingUser) {
                throw new Exception("Потребителят не е намерен");
            }

            // Check if username is changed and already exists
            if ($existingUser['username'] !== $_POST['username']) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $stmt->execute([$_POST['username'], $_POST['user_id']]);
                if ($stmt->fetch()) {
                    throw new Exception("Това потребителско име вече съществува");
                }
            }

            // Prepare SQL based on whether password is being updated
            if (!empty($_POST['password'])) {
                $sql = "UPDATE users SET username = ?, password = ?, full_name = ?, role = ? WHERE id = ?";
                $params = [
                    $_POST['username'],
                    password_hash($_POST['password'], PASSWORD_DEFAULT),
                    $_POST['full_name'],
                    $_POST['role'],
                    $_POST['user_id']
                ];
            } else {
                $sql = "UPDATE users SET username = ?, full_name = ?, role = ? WHERE id = ?";
                $params = [
                    $_POST['username'],
                    $_POST['full_name'],
                    $_POST['role'],
                    $_POST['user_id']
                ];
            }

            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                throw new Exception("Грешка при обновяване на потребителя");
            }

            header("Location: index.php?success=2");
            exit();
        }
    } catch (Exception $e) {
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: index.php");
exit();
?> 