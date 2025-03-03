<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Ремонти";
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
                        <h5 class="card-title"><i class="fas fa-wrench mr-2"></i>Добави нов ремонт</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="process_repair.php">
                            <input type="hidden" name="action" value="add_repair">
                            
                            <div class="form-group">
                                <label for="brand_id">Избери марка:</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <option value="">Избери марка</option>
                                    <?php
                                    $brands = $pdo->query("SELECT * FROM brands ORDER BY brand_name");
                                    while ($brand = $brands->fetch()) {
                                        echo "<option value='" . $brand['id'] . "'>" . htmlspecialchars($brand['brand_name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="model">Модел:</label>
                                <input type="text" name="model" id="model" class="form-control" 
                                       placeholder="Въведете модел">
                            </div>

                            <div class="form-group">
                                <label for="engine">Двигател:</label>
                                <input type="text" name="engine" id="engine" class="form-control" 
                                       placeholder="Въведете двигател">
                            </div>

                            <div class="form-group">
                                <label for="license_plate">Рег. номер:</label>
                                <input type="text" name="license_plate" id="license_plate" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="customer_phone">Тел. номер:</label>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="start_date">Начална дата:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="total_cost">Тотал:</label>
                                <div class="input-group">
                                    <input type="number" name="total_cost" id="total_cost" class="form-control" 
                                           step="0.01" min="0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">лв.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Описание:</label>
                                <textarea name="description" id="description" class="form-control" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Добави ремонт
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-list mr-2"></i>Списък с ремонти</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Марка</th>
                                        <th>Модел</th>
                                        <th>Двигател</th>
                                        <th>Рег. номер</th>
                                        <th>Тел. номер</th>
                                        <th>Дата</th>
                                        <th>Статус</th>
                                        <th>Тотал</th>
                                        <th>Описание</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT r.*, b.brand_name 
                                    FROM repairs r 
                                    JOIN brands b ON r.brand_id = b.id 
                                    WHERE r.username = ? 
                                    ORDER BY CASE 
                                        WHEN r.status = 'pending' THEN 1
                                        WHEN r.status = 'waiting_parts' THEN 2
                                        WHEN r.status = 'completed' THEN 3
                                    END, 
                                    r.start_date DESC";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->execute([$_SESSION['username']]);
                                    $repairs = $stmt->fetchAll();
                                    
                                    
                                    foreach ($repairs as $repair) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($repair['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($repair['brand_name']) . "</td>";
                                        echo "<td>" . (!empty($repair['model']) ? htmlspecialchars($repair['model']) : '-') . "</td>";
                                        echo "<td>" . (!empty($repair['engine']) ? htmlspecialchars($repair['engine']) : '-') . "</td>";
                                        echo "<td>" . htmlspecialchars($repair['license_plate']) . "</td>";
                                        echo "<td>" . htmlspecialchars($repair['customer_phone']) . "</td>";
                                        echo "<td>" . htmlspecialchars($repair['start_date']) . "</td>";
                                        echo "<td><span class='badge badge-" . 
                                            ($repair['status'] == 'pending' ? 'warning' : 
                                            ($repair['status'] == 'completed' ? 'success' : 
                                            ($repair['status'] == 'waiting_parts' ? 'info' : 'secondary'))) . 
                                            "'>" . 
                                            ($repair['status'] == 'pending' ? 'В процес' : 
                                            ($repair['status'] == 'completed' ? 'Завършен' : 
                                            ($repair['status'] == 'waiting_parts' ? 'В чакане на части' : 'Неизвестен'))) . 
                                            "</span></td>";
                                        echo "<td>" . number_format($repair['total_cost'], 2) . " лв.</td>";
                                        echo "<td>" . htmlspecialchars($repair['description']) . "</td>";
                                        echo "<td>
                                                <a href='edit_repair.php?id=" . $repair['id'] . "' class='btn btn-sm btn-primary'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <a href='delete_repair.php?id=" . $repair['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Сигурни ли сте?\")'>
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