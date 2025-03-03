<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Табло";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?> | Автосервиз</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        .dashboard-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        
        .card-header {
            background: linear-gradient(45deg, #2c3e50, #3498db);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .table {
            margin: 0;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            color: #2c3e50;
            font-size: 1rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
        }
        
        .recent-activity {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        .recent-activity h2 {
            color: #2c3e50;
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .activity-list {
            list-style: none;
            padding: 0;
        }
        
        .activity-item {
            padding: 15px;
            border-left: 4px solid #3498db;
            background: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: center;
        }
        
        .activity-item i {
            color: #3498db;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info img {
            border-radius: 50%;
            border: 2px solid #3498db;
        }
        
        .logout-btn {
            color: #e74c3c;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            color: #c0392b;
            text-decoration: none;
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'includes/menu.php'; ?>
        
        <div id="content">
            <?php include 'includes/topbar.php'; ?>
            
            <div class="dashboard-content">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="admin-panel-button mb-4">
                        <a href="admin/index.php" class="btn btn-primary">
                            <i class="fas fa-users-cog"></i> Администраторски панел
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Recent Repairs Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-wrench mr-2"></i>Последни ремонти</h5>
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
                                             r.start_date DESC 
                                             LIMIT 5";
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
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="repairs.php" class="btn btn-primary">
                            <i class="fas fa-list mr-2"></i>Виж всички ремонти
                        </a>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><i class="fas fa-tools mr-2"></i>Активни ремонти</h3>
                        <div class="number">
                            <?php
                            $activeRepairsQuery = "SELECT COUNT(*) as count FROM repairs WHERE status = 'pending'";
                            $activeRepairs = $pdo->query($activeRepairsQuery)->fetch();
                            echo htmlspecialchars($activeRepairs['count']);
                            ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><i class="fas fa-check-circle mr-2"></i>Завършени днес</h3>
                        <div class="number">
                            <?php
                            $todayCompletedQuery = "SELECT COUNT(*) as count 
                                                   FROM repairs 
                                                   WHERE status = 'completed' 
                                                   AND DATE(completion_date) = CURDATE()";
                            $todayCompleted = $pdo->query($todayCompletedQuery)->fetch();
                            echo htmlspecialchars($todayCompleted['count']);
                            ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><i class="fas fa-clipboard-check mr-2"></i>Общо завършени</h3>
                        <div class="number">
                            <?php
                            $totalCompletedQuery = "SELECT COUNT(*) as count 
                                                   FROM repairs 
                                                   WHERE status = 'completed'";
                            $totalCompleted = $pdo->query($totalCompletedQuery)->fetch();
                            echo htmlspecialchars($totalCompleted['count']);
                            ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><i class="fas fa-money-bill-wave mr-2"></i>Приходи днес</h3>
                        <div class="number">
                            <?php
                            $todayRevenueQuery = "SELECT COALESCE(SUM(total_cost), 0) as total 
                                                 FROM repairs 
                                                 WHERE DATE(completion_date) = CURDATE()
                                                 AND status = 'completed'";
                            try {
                                $todayRevenue = $pdo->query($todayRevenueQuery)->fetch();
                                $total = $todayRevenue['total'] ?? 0;
                                echo number_format($total, 2) . " лв.";
                            } catch (PDOException $e) {
                                echo "0.00 лв.";
                                // Optionally log the error
                                error_log("Error calculating today's revenue: " . $e->getMessage());
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2><i class="fas fa-history mr-2"></i>Последна активност</h2>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <i class="fas fa-check"></i>
                            Завършен ремонт - BMW X5 (СА 1234 ВР)
                        </li>
                        <li class="activity-item">
                            <i class="fas fa-user-plus"></i>
                            Нов клиент - Иван Петров
                        </li>
                        <li class="activity-item">
                            <i class="fas fa-car"></i>
                            Нов автомобил за ремонт - Audi A4 (СВ 5678 АН)
                        </li>
                        <li class="activity-item">
                            <i class="fas fa-tools"></i>
                            Започнат ремонт - Mercedes C200 (СА 9012 МР)
                        </li>
                    </ul>
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