<div class="sidebar">
    <div class="sidebar-header">
        <h2>Автосервиз</h2>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Табло
        </a>
        <a href="vehicles.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'vehicles.php') ? 'active' : ''; ?>">
            <i class="fas fa-car"></i>
            Автомобили
        </a>
        <a href="repairs.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'repairs.php') ? 'active' : ''; ?>">
            <i class="fas fa-tools"></i>
            Ремонти
        </a>
        <a href="clients.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'clients.php') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Клиенти
        </a>
        <a href="settings.php" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            Настройки
        </a>
        <?php if (isAdmin()): ?>
            <a href="admin/index.php" class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i>
                Администрация
            </a>
        <?php endif; ?>
    </div>
</div> 