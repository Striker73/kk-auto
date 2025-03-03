<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Редактиране на клиент";

// Get client ID from URL
$client_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch client data
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

// If client not found, redirect back to clients list
if (!$client) {
    header("Location: clients.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?> | Автосервиз</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'includes/menu.php'; ?>
        
        <div id="content">
            <?php include 'includes/topbar.php'; ?>
            
            <div class="dashboard-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-edit mr-2"></i>Редактиране на клиент #<?php echo htmlspecialchars($client['id']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="process_client.php">
                            <input type="hidden" name="action" value="edit_client">
                            <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($client['id']); ?>">
                            
                            <div class="form-group">
                                <label for="name">Име:</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                       value="<?php echo htmlspecialchars($client['name']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="phone">Телефон:</label>
                                <input type="tel" name="phone" id="phone" class="form-control" required
                                       value="<?php echo htmlspecialchars($client['phone']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Имейл:</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="<?php echo htmlspecialchars($client['email']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="address">Адрес:</label>
                                <textarea name="address" id="address" class="form-control"><?php 
                                    echo htmlspecialchars($client['address']); 
                                ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Запази промените
                            </button>
                            <a href="clients.php" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>Отказ
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
</body>
</html> 