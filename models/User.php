<?php
require_once 'config/database.php';

class User {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function authenticate($email, $password) {
        // Check in all user tables
        $tables = ['doctor', 'patient', 'admin'];
        
        foreach ($tables as $table) {
            $user = $this->db->fetchOne(
                "SELECT * FROM {$table} WHERE email = ?", 
                [$email]
            );
            
            if ($user && $user['password'] == $password) {
                $user['user_type'] = $table;
                return $user;
            }
        }
        
        return false;
    }
    
    public function emailExists($email, $excludeTable = null) {
        $tables = ['doctor', 'patient', 'admin'];
        
        if ($excludeTable) {
            $tables = array_diff($tables, [$excludeTable]);
        }
        
        foreach ($tables as $table) {
            $result = $this->db->fetchOne(
                "SELECT id FROM {$table} WHERE email = ?", 
                [$email]
            );
            
            if ($result) {
                return true;
            }
        }
        
        return false;
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>