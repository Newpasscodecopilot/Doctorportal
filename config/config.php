<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dpp');

// Application Configuration
define('APP_NAME', 'Doctor Patient Portal');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost');

// Security Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('BCRYPT_COST', 12);

// File Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_DOC_TYPES', ['pdf']);

// Email Configuration (for future use)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>