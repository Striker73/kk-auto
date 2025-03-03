<?php
session_start();
require_once '../config/database.php';
require_once '../includes/admin_check.php';

requireAdmin();

$page_title = "Добавяне на потребител";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> | Автосервиз</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <div class="wrapper">
        <div id="content" class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5 class="mb-0">Добавяне на нов потребител</h5>
                        </div>
                        <div class="card-body">
                            <form action="process_user.php" method="POST">
                                <input type="hidden" name="action" value="add_user">
                                
                                <div class="form-group">
                                    <label for="username">Потребителско име</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Парола</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="full_name">Име</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>

                                <div class="form-group">
                                    <label for="role">Роля</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="staff">Служител</option>
                                        <option value="mechanic">Механик</option>
                                        <option value="admin">Администратор</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Създай</button>
                                <a href="index.php" class="btn btn-secondary">Отказ</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 
