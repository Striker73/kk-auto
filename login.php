<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вход | Автосервиз</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Вход</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                switch($_GET['error']) {
                    case 'invalid_user':
                        echo "Невалидно потребителско име";
                        break;
                    case 'invalid_password':
                        echo "Невалидна парола";
                        break;
                    case 'unauthorized':
                        echo "Нямате достъп до тази страница";
                        break;
                    case 'system':
                        echo "Системна грешка. Моля, опитайте по-късно";
                        break;
                    default:
                        echo "Възникна грешка при влизането";
                }
                ?>
            </div>
        <?php endif; ?>
        <form action="process_login.php" method="POST">
            <div class="form-group">
                <label for="username">Потребителско име:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Парола:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Вход</button>
        </form>
    </div>
</body>
</html>