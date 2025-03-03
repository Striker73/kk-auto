<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Механици";
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Списък с механици</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Име</th>
                                                <th>Специалност</th>
                                                <th>Активни ремонти</th>
                                                <th>Завършени ремонти</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT u.*, 
                                                     COUNT(CASE WHEN r.status != 'completed' THEN 1 END) as active_repairs,
                                                     COUNT(CASE WHEN r.status = 'completed' THEN 1 END) as completed_repairs
                                                     FROM users u
                                                     LEFT JOIN repairs r ON r.mechanic_id = u.id
                                                     WHERE u.role = 'mechanic'
                                                     GROUP BY u.id";
                                            $mechanics = $pdo->query($query);
                                            
                                            while ($mechanic = $mechanics->fetch()) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($mechanic['id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($mechanic['full_name']) . "</td>";
                                                echo "<td>Общ механик</td>";
                                                echo "<td>" . htmlspecialchars($mechanic['active_repairs']) . "</td>";
                                                echo "<td>" . htmlspecialchars($mechanic['completed_repairs']) . "</td>";
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