<?php
/**
 * Doctor Patient Portal REST API
 * Provides RESTful endpoints for mobile and third-party integrations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/config.php';
require_once '../includes/conn.php';
require_once '../includes/Security.php';

class DPPApi {
    private $con;
    private $method;
    private $endpoint;
    private $params;
    
    public function __construct($con) {
        $this->con = $con;
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Parse URL
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = str_replace('/api/', '', $url);
        $urlParts = explode('/', trim($url, '/'));
        
        $this->endpoint = $urlParts[0] ?? '';
        $this->params = array_slice($urlParts, 1);
    }
    
    public function processRequest() {
        try {
            switch ($this->endpoint) {
                case 'auth':
                    return $this->handleAuth();
                case 'doctors':
                    return $this->handleDoctors();
                case 'patients':
                    return $this->handlePatients();
                case 'appointments':
                    return $this->handleAppointments();
                case 'messages':
                    return $this->handleMessages();
                case 'stats':
                    return $this->handleStats();
                default:
                    return $this->response(['error' => 'Endpoint not found'], 404);
            }
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            return $this->response(['error' => 'Internal server error'], 500);
        }
    }
    
    private function handleAuth() {
        switch ($this->method) {
            case 'POST':
                return $this->authenticate();
            default:
                return $this->response(['error' => 'Method not allowed'], 405);
        }
    }
    
    private function authenticate() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['email']) || !isset($input['password'])) {
            return $this->response(['error' => 'Email and password required'], 400);
        }
        
        $email = Security::sanitizeInput($input['email']);
        $password = $input['password'];
        
        if (!Security::validateEmail($email)) {
            return $this->response(['error' => 'Invalid email format'], 400);
        }
        
        // Check each user type
        $tables = ['doctor', 'patient', 'admin'];
        
        foreach ($tables as $table) {
            $stmt = executeQuery($this->con, "SELECT * FROM {$table} WHERE email = ?", [$email]);
            $user = fetchSingleResult($stmt);
            
            if ($user) {
                $passwordMatch = false;
                
                if (password_verify($password, $user['password'])) {
                    $passwordMatch = true;
                } elseif ($user['password'] == $password) {
                    $passwordMatch = true;
                }
                
                if ($passwordMatch) {
                    // Generate JWT token (simplified)
                    $token = base64_encode(json_encode([
                        'user_id' => $user[array_keys($user)[0]],
                        'email' => $email,
                        'type' => $table,
                        'exp' => time() + 3600
                    ]));
                    
                    return $this->response([
                        'success' => true,
                        'token' => $token,
                        'user' => [
                            'id' => $user[array_keys($user)[0]],
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'type' => $table
                        ]
                    ]);
                }
            }
        }
        
        return $this->response(['error' => 'Invalid credentials'], 401);
    }
    
    private function handleDoctors() {
        switch ($this->method) {
            case 'GET':
                if (isset($this->params[0])) {
                    return $this->getDoctor($this->params[0]);
                } else {
                    return $this->getDoctors();
                }
            default:
                return $this->response(['error' => 'Method not allowed'], 405);
        }
    }
    
    private function getDoctors() {
        $stmt = executeQuery($this->con, 
            "SELECT did, name, email, age, gender, phone_number, doccat, status 
             FROM doctor WHERE status = 'success' ORDER BY name");
        
        $doctors = fetchResults($stmt);
        
        return $this->response([
            'success' => true,
            'data' => $doctors,
            'count' => count($doctors)
        ]);
    }
    
    private function getDoctor($id) {
        $stmt = executeQuery($this->con, 
            "SELECT did, name, email, age, gender, phone_number, doccat, status 
             FROM doctor WHERE did = ? AND status = 'success'", [$id]);
        
        $doctor = fetchSingleResult($stmt);
        
        if ($doctor) {
            return $this->response([
                'success' => true,
                'data' => $doctor
            ]);
        } else {
            return $this->response(['error' => 'Doctor not found'], 404);
        }
    }
    
    private function handleAppointments() {
        switch ($this->method) {
            case 'GET':
                return $this->getAppointments();
            case 'POST':
                return $this->createAppointment();
            default:
                return $this->response(['error' => 'Method not allowed'], 405);
        }
    }
    
    private function getAppointments() {
        $stmt = executeQuery($this->con, 
            "SELECT a.*, d.name as doctor_name, p.name as patient_name 
             FROM appointments a 
             LEFT JOIN doctor d ON a.docid = d.docid 
             LEFT JOIN patient p ON a.pid = p.pid 
             ORDER BY a.date DESC, a.time DESC");
        
        $appointments = fetchResults($stmt);
        
        return $this->response([
            'success' => true,
            'data' => $appointments,
            'count' => count($appointments)
        ]);
    }
    
    private function createAppointment() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['docid', 'pid', 'date', 'time', 'problem'];
        foreach ($required as $field) {
            if (!isset($input[$field])) {
                return $this->response(['error' => "Field {$field} is required"], 400);
            }
        }
        
        $stmt = executeQuery($this->con, 
            "INSERT INTO appointments (docid, pid, date, time, problem, status) 
             VALUES (?, ?, ?, ?, ?, 'pending')",
            [
                $input['docid'], 
                $input['pid'], 
                $input['date'], 
                $input['time'], 
                $input['problem']
            ]
        );
        
        if ($stmt) {
            return $this->response([
                'success' => true,
                'message' => 'Appointment created successfully',
                'id' => mysqli_insert_id($this->con)
            ], 201);
        } else {
            return $this->response(['error' => 'Failed to create appointment'], 500);
        }
    }
    
    private function handleStats() {
        if ($this->method !== 'GET') {
            return $this->response(['error' => 'Method not allowed'], 405);
        }
        
        // Get statistics
        $doctorCount = fetchSingleResult(executeQuery($this->con, 
            "SELECT COUNT(*) as count FROM doctor WHERE status = 'success'"))['count'];
        
        $patientCount = fetchSingleResult(executeQuery($this->con, 
            "SELECT COUNT(*) as count FROM patient"))['count'];
        
        $appointmentCount = fetchSingleResult(executeQuery($this->con, 
            "SELECT COUNT(*) as count FROM appointments"))['count'];
        
        $pendingAppointments = fetchSingleResult(executeQuery($this->con, 
            "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'"))['count'];
        
        return $this->response([
            'success' => true,
            'data' => [
                'doctors' => (int)$doctorCount,
                'patients' => (int)$patientCount,
                'appointments' => (int)$appointmentCount,
                'pending_appointments' => (int)$pendingAppointments
            ]
        ]);
    }
    
    private function response($data, $status = 200) {
        http_response_code($status);
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

// Initialize and process API request
$api = new DPPApi($con);
echo $api->processRequest();
?>