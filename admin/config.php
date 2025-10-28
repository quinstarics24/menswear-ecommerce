<?php

// Default username: admin
// Default password: admin123
if (!defined('ADMIN_USER')) define('ADMIN_USER', 'admin');
// Store a hashed password. Change 'admin123' to your desired password then save.
if (!defined('ADMIN_PASS_HASH')) define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_DEFAULT));

// Security / lockout settings
if (!defined('ADMIN_MAX_ATTEMPTS')) define('ADMIN_MAX_ATTEMPTS', 5);
if (!defined('ADMIN_LOCKOUT_SECONDS')) define('ADMIN_LOCKOUT_SECONDS', 300); // 5 minutes