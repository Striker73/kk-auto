<?php
session_start();
require_once '../config/database.php';
require_once '../includes/admin_check.php';

requireAdmin();

if (isset($_GET['id'])) {
    try {
        // Add debug logging
        error_log("Attempting to delete user ID: " . $_GET['id']);
        error_log("Current user ID: " . $_SESSION['user_id']);

        // Don't allow deleting yourself
        if ($_GET['id'] == $_SESSION['user_id']) {
            error_log("Attempted to delete own account");
            throw new Exception("Не можете да изтриете собствения си акаунт");
        }

        // Check if user exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $checkStmt->execute([$_GET['id']]);
        if (!$checkStmt->fetch()) {
            error_log("User not found: " . $_GET['id']);
            throw new Exception("Потребителят не е намерен");
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$_GET['id']]);

        if ($result) {
            error_log("Successfully deleted user ID: " . $_GET['id']);
            header("Location: index.php?success=3");
            exit();
        } else {
            error_log("Failed to delete user. PDO Error: " . print_r($stmt->errorInfo(), true));
            throw new Exception("Грешка при изтриване на потребителя");
        }
    } catch (Exception $e) {
        error_log("Delete user error: " . $e->getMessage());
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: index.php");
exit();
?> 