<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="img/logo.png" alt="Logo" class="img-fluid">
        </div>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo $page_title === 'Табло' ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Табло</span>
            </a>
        </li>
        <li class="<?php echo $page_title === 'Ремонти' ? 'active' : ''; ?>">
            <a href="repairs.php">
                <i class="fas fa-wrench"></i>
                <span>Ремонти</span>
            </a>
        </li>
        <li class="<?php echo $page_title === 'Клиенти' ? 'active' : ''; ?>">
            <a href="clients.php">
                <i class="fas fa-users"></i>
                <span>Клиенти</span>
            </a>
        </li>
        <li class="<?php echo $page_title === 'Механици' ? 'active' : ''; ?>">
            <a href="mechanics.php">
                <i class="fas fa-user-cog"></i>
                <span>Механици</span>
            </a>
        </li>
        <li class="<?php echo $page_title === 'Настройки' ? 'active' : ''; ?>">
            <a href="settings.php">
                <i class="fas fa-cog"></i>
                <span>Настройки</span>
            </a>
        </li>
        <li>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Изход</span>
            </a>
        </li>
    </ul>
</nav>
