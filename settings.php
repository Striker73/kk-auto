<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Настройки";

// Get current user's data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
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
            
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Настройки на профила</h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success">
                                        Настройките са запазени успешно!
                                    </div>
                                <?php endif; ?>

                                <form action="process_settings.php" method="POST">
                                    <div class="form-group">
                                        <label for="username">Потребителско име</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="full_name">Име</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password">Нова парола (оставете празно ако не желаете да я променяте)</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password">Потвърдете новата парола</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Запази промените</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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