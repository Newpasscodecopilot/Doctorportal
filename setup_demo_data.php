<?php
/**
 * Setup Demo Data for Doctor Patient Portal
 * This script creates demo users with properly hashed passwords
 */

require_once 'config/config.php';
require_once 'includes/conn.php';

echo "Setting up demo data for Doctor Patient Portal...\n";

// Create demo admin user
$adminEmail = 'admin@test.com';
$adminPassword = password_hash('123456', PASSWORD_BCRYPT);
$adminName = 'System Administrator';

$stmt = executeQuery($con, "UPDATE admin SET password = ?, name = ? WHERE email = ?", 
    [$adminPassword, $adminName, $adminEmail]);

if ($stmt) {
    echo "✓ Admin user updated with secure password\n";
} else {
    echo "✗ Failed to update admin user\n";
}

// Create demo doctor
$doctorData = [
    'name' => 'Dr. John Smith',
    'email' => 'doctor@demo.com',
    'password' => password_hash('demo123', PASSWORD_BCRYPT),
    'age' => 45,
    'gender' => 'Male',
    'phone_number' => '9876543210',
    'address' => '123 Medical Center, Healthcare City',
    'docid' => 'DOC001',
    'adharno' => '123456789012',
    'type' => 'doc',
    'doccat' => 'Cardiologist',
    'status' => 'success'
];

// Check if doctor exists
$existingDoctor = executeQuery($con, "SELECT * FROM doctor WHERE email = ?", [$doctorData['email']]);
$doctor = fetchSingleResult($existingDoctor);

if (!$doctor) {
    $stmt = executeQuery($con, 
        "INSERT INTO doctor (name, email, password, age, gender, phone_number, address, docid, adharno, type, doccat, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $doctorData['name'], $doctorData['email'], $doctorData['password'], 
            $doctorData['age'], $doctorData['gender'], $doctorData['phone_number'],
            $doctorData['address'], $doctorData['docid'], $doctorData['adharno'],
            $doctorData['type'], $doctorData['doccat'], $doctorData['status']
        ]
    );
    
    if ($stmt) {
        echo "✓ Demo doctor created successfully\n";
        
        // Create doctor directory
        $doctorDir = "doctor/" . $doctorData['docid'];
        if (!file_exists($doctorDir)) {
            mkdir($doctorDir, 0755, true);
            mkdir($doctorDir . "/img", 0755, true);
            echo "✓ Doctor directory created\n";
        }
    } else {
        echo "✗ Failed to create demo doctor\n";
    }
} else {
    // Update existing doctor with hashed password
    $stmt = executeQuery($con, "UPDATE doctor SET password = ? WHERE email = ?", 
        [$doctorData['password'], $doctorData['email']]);
    echo "✓ Demo doctor password updated\n";
}

// Create demo patient
$patientData = [
    'name' => 'Jane Doe',
    'email' => 'patient@demo.com',
    'password' => password_hash('demo123', PASSWORD_BCRYPT),
    'age' => 30,
    'gender' => 'Female',
    'phone' => '9876543211',
    'address' => '456 Patient Street, Health City',
    'adharno' => 'PAT123456789',
    'type' => 'pat'
];

// Check if patient exists
$existingPatient = executeQuery($con, "SELECT * FROM patient WHERE email = ?", [$patientData['email']]);
$patient = fetchSingleResult($existingPatient);

if (!$patient) {
    $stmt = executeQuery($con, 
        "INSERT INTO patient (name, email, password, age, gender, phone, address, adharno, type) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $patientData['name'], $patientData['email'], $patientData['password'], 
            $patientData['age'], $patientData['gender'], $patientData['phone'],
            $patientData['address'], $patientData['adharno'], $patientData['type']
        ]
    );
    
    if ($stmt) {
        echo "✓ Demo patient created successfully\n";
        
        // Create patient directory
        $patientDir = "patient/" . $patientData['adharno'];
        if (!file_exists($patientDir)) {
            mkdir($patientDir, 0755, true);
            mkdir($patientDir . "/img", 0755, true);
            mkdir($patientDir . "/reports", 0755, true);
            echo "✓ Patient directory created\n";
        }
    } else {
        echo "✗ Failed to create demo patient\n";
    }
} else {
    // Update existing patient with hashed password
    $stmt = executeQuery($con, "UPDATE patient SET password = ? WHERE email = ?", 
        [$patientData['password'], $patientData['email']]);
    echo "✓ Demo patient password updated\n";
}

echo "\nDemo data setup complete!\n";
echo "\nLogin Credentials:\n";
echo "==================\n";
echo "Admin: admin@test.com / 123456\n";
echo "Doctor: doctor@demo.com / demo123\n";
echo "Patient: patient@demo.com / demo123\n";
echo "\nAll passwords are now securely hashed.\n";

// Close database connection
mysqli_close($con);
?>