<?php
session_start();
require_once 'config/database.php';
require_once 'includes/date_helper.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug information
        error_log("POST data: " . print_r($_POST, true));

        if ($_POST['action'] === 'add_repair') {
            // Ensure start_date is formatted correctly
            $start_date = formatDateForMySQL($_POST['start_date']);

            $stmt = $pdo->prepare("INSERT INTO repairs (
                brand_id, 
                model,
                engine,
                license_plate, 
                customer_phone, 
                start_date, 
                status, 
                total_cost,
                description,
                username
            ) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?)");

            $params = [
                $_POST['brand_id'],
                $_POST['model'] ?? '',
                $_POST['engine'] ?? '',
                $_POST['license_plate'],
                $_POST['customer_phone'],
                $start_date,
                $_POST['total_cost'],
                $_POST['description'],
                $_SESSION['username'] // Use the username from the session
            ];

            // Debug information
            error_log("Query parameters: " . print_r($params, true));
            
            $result = $stmt->execute($params);

            if (!$result) {
                error_log("Database error: " . print_r($stmt->errorInfo(), true));
                throw new PDOException("Failed to add repair: " . $stmt->errorInfo()[2]);
            }

            header("Location: repairs.php?success=1");
            exit();
        }
        elseif ($_POST['action'] === 'edit_repair') {
            // Get the existing repair data
            $stmt = $pdo->prepare("SELECT * FROM repairs WHERE id = ?");
            $stmt->execute([$_POST['repair_id']]);
            $existingRepair = $stmt->fetch();

            // Debug information
            error_log("Existing repair: " . print_r($existingRepair, true));

            // Prepare completion date based on status
            $completion_date = null;
            if ($_POST['status'] === 'completed') {
                $completion_date = $_POST['completion_date'] ?? date('Y-m-d');
            }

            $stmt = $pdo->prepare("UPDATE repairs 
                                 SET brand_id = ?, 
                                     model = ?,
                                     engine = ?,
                                     license_plate = ?,
                                     customer_phone = ?,
                                     start_date = ?, 
                                     status = ?, 
                                     completion_date = ?, 
                                     total_cost = ?,
                                     description = ? 
                                 WHERE id = ?");
            
            $params = [
                $_POST['brand_id'],
                $_POST['model'] ?? $existingRepair['model'],
                $_POST['engine'] ?? $existingRepair['engine'],
                $_POST['license_plate'] ?? $existingRepair['license_plate'],
                $_POST['customer_phone'] ?? $existingRepair['customer_phone'],
                $_POST['start_date'],
                $_POST['status'],
                $completion_date,
                $_POST['total_cost'] ?? $existingRepair['total_cost'],
                $_POST['description'],
                $_POST['repair_id']
            ];

            // Debug information
            error_log("Update parameters: " . print_r($params, true));
            
            $result = $stmt->execute($params);

            if (!$result) {
                error_log("Database error: " . print_r($stmt->errorInfo(), true));
                throw new PDOException("Failed to update repair: " . $stmt->errorInfo()[2]);
            }

            header("Location: repairs.php?success=2");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: repairs.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

header("Location: repairs.php");
exit();
?>