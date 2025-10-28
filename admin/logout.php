<?php
session_start();
// clear admin session only (preserve other session data if desired)
unset($_SESSION['is_admin'], $_SESSION['admin_user']);
session_regenerate_id(true);
header('Location: login.php'); exit;