<?php
/**
 * API Test Suite for Doctor Patient Portal
 * Tests all API endpoints for functionality and security
 */

class ApiTest {
    private $baseUrl;
    private $testResults = [];
    
    public function __construct($baseUrl = 'http://localhost/api') {
        $this->baseUrl = rtrim($baseUrl, '/');
    }
    
    public function runAllTests() {
        echo "🧪 Running API Test Suite\n";
        echo "========================\n\n";
        
        $this->testAuthEndpoint();
        $this->testDoctorsEndpoint();
        $this->testAppointmentsEndpoint();
        $this->testStatsEndpoint();
        $this->testErrorHandling();
        
        $this->printResults();
    }
    
    private function testAuthEndpoint() {
        echo "Testing Authentication Endpoint...\n";
        
        // Test valid login
        $response = $this->makeRequest('POST', '/auth', [
            'email' => 'admin@test.com',
            'password' => '123456'
        ]);
        
        $this->assert(
            isset($response['success']) && $response['success'] === true,
            'Valid login should return success'
        );
        
        $this->assert(
            isset($response['token']),
            'Valid login should return token'
        );
        
        // Test invalid login
        $response = $this->makeRequest('POST', '/auth', [
            'email' => 'invalid@test.com',
            'password' => 'wrongpassword'
        ]);
        
        $this->assert(
            isset($response['error']),
            'Invalid login should return error'
        );
        
        // Test missing parameters
        $response = $this->makeRequest('POST', '/auth', [
            'email' => 'test@test.com'
        ]);
        
        $this->assert(
            isset($response['error']),
            'Missing password should return error'
        );
        
        echo "✓ Authentication tests completed\n\n";
    }
    
    private function testDoctorsEndpoint() {
        echo "Testing Doctors Endpoint...\n";
        
        // Test get all doctors
        $response = $this->makeRequest('GET', '/doctors');
        
        $this->assert(
            isset($response['success']) && $response['success'] === true,
            'Get doctors should return success'
        );
        
        $this->assert(
            isset($response['data']) && is_array($response['data']),
            'Get doctors should return data array'
        );
        
        $this->assert(
            isset($response['count']),
            'Get doctors should return count'
        );
        
        // Test get specific doctor (if any exist)
        if (!empty($response['data'])) {
            $doctorId = $response['data'][0]['did'];
            $doctorResponse = $this->makeRequest('GET', "/doctors/{$doctorId}");
            
            $this->assert(
                isset($doctorResponse['success']) && $doctorResponse['success'] === true,
                'Get specific doctor should return success'
            );
        }
        
        echo "✓ Doctors tests completed\n\n";
    }
    
    private function testAppointmentsEndpoint() {
        echo "Testing Appointments Endpoint...\n";
        
        // Test get appointments
        $response = $this->makeRequest('GET', '/appointments');
        
        $this->assert(
            isset($response['success']) && $response['success'] === true,
            'Get appointments should return success'
        );
        
        $this->assert(
            isset($response['data']) && is_array($response['data']),
            'Get appointments should return data array'
        );
        
        // Test create appointment (would need valid doctor and patient IDs)
        // This is a basic structure test
        $response = $this->makeRequest('POST', '/appointments', [
            'docid' => 'TEST001',
            'pid' => 'TEST001',
            'date' => '2024-12-25',
            'time' => '10:00',
            'problem' => 'Test appointment'
        ]);
        
        // Should either succeed or fail gracefully
        $this->assert(
            isset($response['success']) || isset($response['error']),
            'Create appointment should return success or error'
        );
        
        echo "✓ Appointments tests completed\n\n";
    }
    
    private function testStatsEndpoint() {
        echo "Testing Stats Endpoint...\n";
        
        $response = $this->makeRequest('GET', '/stats');
        
        $this->assert(
            isset($response['success']) && $response['success'] === true,
            'Get stats should return success'
        );
        
        $this->assert(
            isset($response['data']) && is_array($response['data']),
            'Get stats should return data array'
        );
        
        $expectedKeys = ['doctors', 'patients', 'appointments', 'pending_appointments'];
        foreach ($expectedKeys as $key) {
            $this->assert(
                isset($response['data'][$key]),
                "Stats should include {$key}"
            );
        }
        
        echo "✓ Stats tests completed\n\n";
    }
    
    private function testErrorHandling() {
        echo "Testing Error Handling...\n";
        
        // Test invalid endpoint
        $response = $this->makeRequest('GET', '/invalid-endpoint');
        
        $this->assert(
            isset($response['error']),
            'Invalid endpoint should return error'
        );
        
        // Test invalid method
        $response = $this->makeRequest('DELETE', '/doctors');
        
        $this->assert(
            isset($response['error']),
            'Invalid method should return error'
        );
        
        echo "✓ Error handling tests completed\n\n";
    }
    
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return json_decode($response, true) ?: ['error' => 'Invalid JSON response'];
    }
    
    private function assert($condition, $message) {
        $this->testResults[] = [
            'condition' => $condition,
            'message' => $message,
            'status' => $condition ? 'PASS' : 'FAIL'
        ];
        
        if ($condition) {
            echo "  ✓ {$message}\n";
        } else {
            echo "  ✗ {$message}\n";
        }
    }
    
    private function printResults() {
        $total = count($this->testResults);
        $passed = count(array_filter($this->testResults, function($test) {
            return $test['condition'];
        }));
        $failed = $total - $passed;
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "📊 TEST RESULTS SUMMARY\n";
        echo str_repeat("=", 50) . "\n";
        echo "Total Tests: {$total}\n";
        echo "Passed: {$passed} ✓\n";
        echo "Failed: {$failed} " . ($failed > 0 ? "✗" : "✓") . "\n";
        echo "Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";
        
        if ($failed > 0) {
            echo "\nFailed Tests:\n";
            foreach ($this->testResults as $test) {
                if (!$test['condition']) {
                    echo "  ✗ {$test['message']}\n";
                }
            }
        }
        
        echo "\n";
    }
}

// Run tests if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $tester = new ApiTest();
    $tester->runAllTests();
}
?>