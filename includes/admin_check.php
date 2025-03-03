<?php
function isAdmin() {
    if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role'])) {
        return false;
    }
    return $_SESSION['role'] === 'admin';
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php?error=unauthorized");
        exit();
    }
}
?> 