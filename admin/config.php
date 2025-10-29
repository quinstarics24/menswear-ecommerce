<?php
// ========================
// Admin Configuration
// ========================

// Default username and password
if (!defined('ADMIN_USER')) define('ADMIN_USER', 'admin');

// Default password: admin123
// You can change it by generating a new hash with:
//    password_hash('yourNewPassword', PASSWORD_DEFAULT);
if (!defined('ADMIN_PASS_HASH')) define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_DEFAULT));

// Security & lockout settings
if (!defined('ADMIN_MAX_ATTEMPTS')) define('ADMIN_MAX_ATTEMPTS', 5);
if (!defined('ADMIN_LOCKOUT_SECONDS')) define('ADMIN_LOCKOUT_SECONDS', 300); // 5 minutes lockout

// ========================
// Session Setup
// ========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================
// Admin Access Protection
// ========================
function require_admin() {
    if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        // Not logged in — redirect to login
        header('Location: login.php');
        exit;
    }
}
