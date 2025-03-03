<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Клиенти";
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
                        <h5 class="card-title"><i class="fas fa-user-plus mr-2"></i>Добави нов клиент</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="process_client.php">
                            <input type="hidden" name="action" value="add_client">
                            
                            <div class="form-group">
                                <label for="name">Име:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Телефон:</label>
                                <input type="tel" name="phone" id="phone" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Имейл:</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="address">Адрес:</label>
                                <textarea name="address" id="address" class="form-control"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Добави клиент
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-users mr-2"></i>Списък с клиенти</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Име</th>
                                        <th>Телефон</th>
                                        <th>Имейл</th>
                                        <th>Адрес</th>
                                        <th>Дата на създаване</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM clients ORDER BY created_at DESC";
                                    $clients = $pdo->query($query);
                                    
                                    while ($client = $clients->fetch()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($client['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($client['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($client['phone']) . "</td>";
                                        echo "<td>" . htmlspecialchars($client['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($client['address']) . "</td>";
                                        echo "<td>" . htmlspecialchars($client['created_at']) . "</td>";
                                        echo "<td>
                                                <a href='edit_client.php?id=" . $client['id'] . "' class='btn btn-sm btn-primary'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <a href='delete_client.php?id=" . $client['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Сигурни ли сте?\")'>
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