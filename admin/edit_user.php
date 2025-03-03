<?php
session_start();
require_once '../config/database.php';
require_once '../includes/admin_check.php';

requireAdmin();

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// If user not found, redirect back
if (!$user) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редактиране на потребител | Автосервиз</title>
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
                            <h5 class="mb-0">Редактиране на потребител</h5>
                        </div>
                        <div class="card-body">
                            <form action="process_user.php" method="POST">
                                <input type="hidden" name="action" value="edit_user">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                
                                <div class="form-group">
                                    <label for="username">Потребителско име</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Нова парола (оставете празно за да не се променя)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="form-group">
                                    <label for="full_name">Име</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="role">Роля</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Служител</option>
                                        <option value="mechanic" <?php echo $user['role'] == 'mechanic' ? 'selected' : ''; ?>>Механик</option>
                                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Администратор</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Запази промените</button>
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