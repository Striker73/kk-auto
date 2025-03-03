<?php
session_start();
require_once 'config/database.php';
require_once 'includes/date_helper.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Редактиране на ремонт";

// Get repair ID from URL
$repair_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch repair data
$stmt = $pdo->prepare("SELECT * FROM repairs WHERE id = ?");
$stmt->execute([$repair_id]);
$repair = $stmt->fetch();

// If repair not found, redirect back to repairs list
if (!$repair) {
    header("Location: repairs.php");
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
                            <i class="fas fa-edit mr-2"></i>Редактиране на ремонт #<?php echo htmlspecialchars($repair['id']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="process_repair.php">
                            <input type="hidden" name="action" value="edit_repair">
                            <input type="hidden" name="repair_id" value="<?php echo htmlspecialchars($repair['id']); ?>">
                            
                            <div class="form-group">
                                <label for="brand_id">Марка:</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <?php
                                    $brands = $pdo->query("SELECT * FROM brands ORDER BY brand_name");
                                    while ($brand = $brands->fetch()) {
                                        $selected = ($brand['id'] == $repair['brand_id']) ? 'selected' : '';
                                        echo "<option value='" . $brand['id'] . "' " . $selected . ">" 
                                            . htmlspecialchars($brand['brand_name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="model">Модел:</label>
                                <input type="text" name="model" id="model" class="form-control" required 
                                       value="<?php echo htmlspecialchars($repair['model']); ?>"
                                       placeholder="Въведете модел">
                            </div>

                            <div class="form-group">
                                <label for="engine">Двигател:</label>
                                <input type="text" name="engine" id="engine" class="form-control" required 
                                       value="<?php echo htmlspecialchars($repair['engine']); ?>"
                                       placeholder="Въведете двигател">
                            </div>

                            <div class="form-group">
                                <label for="start_date">Дата</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="<?php echo date('Y-m-d', strtotime($repair['start_date'])); ?>"
                                       data-date="<?php echo formatDate($repair['start_date']); ?>"
                                       min="<?php echo date('Y-m-d', strtotime('-1 year')); ?>"
                                       max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="status">Статус:</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="pending" <?php echo $repair['status'] == 'pending' ? 'selected' : ''; ?>>В процес</option>
                                    <option value="waiting_parts" <?php echo $repair['status'] == 'waiting_parts' ? 'selected' : ''; ?>>В чакане на части</option>
                                    <option value="completed" <?php echo $repair['status'] == 'completed' ? 'selected' : ''; ?>>Завършен</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="completion_date">Дата на завършване:</label>
                                <input type="date" name="completion_date" id="completion_date" class="form-control" 
                                       value="<?php echo htmlspecialchars($repair['completion_date'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="total_cost">Тотал:</label>
                                <div class="input-group">
                                    <input type="number" name="total_cost" id="total_cost" class="form-control" 
                                           step="0.01" min="0" required
                                           value="<?php echo htmlspecialchars($repair['total_cost']); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">лв.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Описание:</label>
                                <textarea name="description" id="description" class="form-control" required><?php 
                                    echo htmlspecialchars($repair['description']); 
                                ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Запази промените
                            </button>
                            <a href="repairs.php" class="btn btn-secondary">
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

            // Show/hide completion date based on status
            $('#status').change(function() {
                if ($(this).val() === 'completed') {
                    $('#completion_date').prop('required', true);
                    $('#completion_date').closest('.form-group').show();
                } else {
                    $('#completion_date').prop('required', false);
                    $('#completion_date').closest('.form-group').hide();
                }
            }).trigger('change');
        });
    </script>
</body>
</html> 