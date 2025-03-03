<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['action'] === 'add_client') {
            // Validate required fields
            if (empty($_POST['name']) || empty($_POST['phone'])) {
                throw new PDOException("Name and phone are required");
            }

            $stmt = $pdo->prepare("INSERT INTO clients (name, phone, email, address) 
                                 VALUES (?, ?, ?, ?)");
            
            $result = $stmt->execute([
                $_POST['name'],
                $_POST['phone'],
                $_POST['email'] ?? null,
                $_POST['address'] ?? null
            ]);

            if (!$result) {
                throw new PDOException("Failed to save client");
            }

            header("Location: clients.php?success=1");
            exit();
        }
        elseif ($_POST['action'] === 'edit_client') {
            // Get the existing client data
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $stmt->execute([$_POST['client_id']]);
            $existingClient = $stmt->fetch();

            // Update the client
            $stmt = $pdo->prepare("UPDATE clients 
                                 SET name = ?, 
                                     phone = ?,
                                     email = ?,
                                     address = ?
                                 WHERE id = ?");
            
            $result = $stmt->execute([
                $_POST['name'],
                $_POST['phone'],
                $_POST['email'] ?? $existingClient['email'],
                $_POST['address'] ?? $existingClient['address'],
                $_POST['client_id']
            ]);

            if (!$result) {
                throw new PDOException("Failed to update client");
            }

            header("Location: clients.php?success=2");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: clients.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: clients.php");
exit();
?> 