<?php
class Security {
    
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validatePhone($phone) {
        return preg_match('/^[0-9]{10}$/', $phone);
    }
    
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['useremail']) && isset($_SESSION['userpass']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: index.php');
            exit;
        }
    }
    
    public static function logout() {
        session_destroy();
        setcookie('dpp', '', time() - 3600, '/');
        header('Location: index.php');
        exit;
    }
    
    public static function preventSQLInjection($input) {
        global $con;
        if (isset($con)) {
            return mysqli_real_escape_string($con, $input);
        }
        return addslashes($input);
    }
    
    public static function validateFileUpload($file, $allowedTypes = [], $maxSize = null) {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }
        
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
        
        if ($maxSize && $file['size'] > $maxSize) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
            throw new RuntimeException('Invalid file format.');
        }
        
        return true;
    }
}
?>