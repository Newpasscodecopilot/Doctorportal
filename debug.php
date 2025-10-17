<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing includes...\n";

try {
    include('includes/conn.php');
    echo "Database connection: OK\n";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "PHP version: " . phpversion() . "\n";
echo "MySQL extension: " . (extension_loaded('mysqli') ? 'loaded' : 'not loaded') . "\n";
?>