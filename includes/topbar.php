<div class="top-bar">
    <div class="d-flex align-items-center">
        <button type="button" id="sidebarCollapse" class="btn">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0 ml-3"><?php echo $page_title; ?></h4>
    </div>
    <div class="user-info">
        <span class="mr-3 d-none d-md-inline">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span class="d-none d-md-inline">Изход</span>
        </a>
    </div>
</div> 