<?php
session_start();
require_once '../config/database.php';
require_once '../includes/admin_check.php';

requireAdmin();

$page_title = "Администраторски панел";
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
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center my-4">
                        <h1>Управление на потребители</h1>
                        <a href="../dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Обратно към таблото
                        </a>
                    </div>
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            switch($_GET['success']) {
                                case '1':
                                    echo "Потребителят е създаден успешно.";
                                    break;
                                case '2':
                                    echo "Потребителят е обновен успешно.";
                                    break;
                                case '3':
                                    echo "Потребителят е изтрит успешно.";
                                    break;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Потребители</h5>
                            <a href="add_user.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Нов потребител
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Потребителско име</th>
                                            <th>Име</th>
                                            <th>Роля</th>
                                            <th>Създаден на</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM users ORDER BY created_at DESC";
                                        $users = $pdo->query($query);
                                        
                                        while ($user = $users->fetch()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user['created_at']) . "</td>";
                                            echo "<td>
                                                    <a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-primary'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-danger' 
                                                       onclick='return confirm(\"Сигурни ли сте, че искате да изтриете този потребител?\")'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 