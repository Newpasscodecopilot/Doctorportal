<?php
// Include configuration
require_once __DIR__ . '/../config/config.php';

$server_name = DB_HOST;
$user_name = DB_USER;
$password = DB_PASS;
$db_name = DB_NAME;

$con = mysqli_connect($server_name, $user_name, $password, $db_name);

// Check connection
if (!$con) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Connection failed. Please try again later.");
}

// Set charset to prevent SQL injection
mysqli_set_charset($con, "utf8");

// Function to execute prepared statements safely
function executeQuery($con, $sql, $params = [], $types = '') {
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($con));
        return false;
    }
    
    if (!empty($params)) {
        if (empty($types)) {
            $types = str_repeat('s', count($params));
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    
    return $stmt;
}

// Function to fetch results safely
function fetchResults($stmt) {
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        return [];
    }
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data;
}

// Function to fetch single result safely
function fetchSingleResult($stmt) {
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        return null;
    }
    
    return mysqli_fetch_assoc($result);
}
?>