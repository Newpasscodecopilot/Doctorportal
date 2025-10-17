<?php
/**
 * Doctor Patient Portal Installation Script
 * This script sets up the database and creates initial configuration
 */

// Prevent direct access in production
if (file_exists('config/installed.lock')) {
    die('Application is already installed. Delete config/installed.lock to reinstall.');
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Handle form submissions
if ($_POST) {
    switch ($step) {
        case 1:
            // Database configuration
            $host = $_POST['db_host'] ?? 'localhost';
            $user = $_POST['db_user'] ?? '';
            $pass = $_POST['db_pass'] ?? '';
            $name = $_POST['db_name'] ?? 'dpp';
            
            // Test database connection
            $conn = @mysqli_connect($host, $user, $pass);
            if (!$conn) {
                $error = 'Could not connect to database: ' . mysqli_connect_error();
            } else {
                // Create database if it doesn't exist
                $createDb = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$name`");
                if (!$createDb) {
                    $error = 'Could not create database: ' . mysqli_error($conn);
                } else {
                    // Save configuration
                    $config = "<?php\n";
                    $config .= "// Database Configuration\n";
                    $config .= "define('DB_HOST', '$host');\n";
                    $config .= "define('DB_USER', '$user');\n";
                    $config .= "define('DB_PASS', '$pass');\n";
                    $config .= "define('DB_NAME', '$name');\n\n";
                    $config .= "// Application Configuration\n";
                    $config .= "define('APP_NAME', 'Doctor Patient Portal');\n";
                    $config .= "define('APP_VERSION', '2.0.0');\n";
                    $config .= "define('BASE_URL', 'http://' . \$_SERVER['HTTP_HOST'] . dirname(\$_SERVER['SCRIPT_NAME']));\n\n";
                    $config .= "// Security Configuration\n";
                    $config .= "define('SESSION_TIMEOUT', 3600);\n";
                    $config .= "define('BCRYPT_COST', 12);\n\n";
                    $config .= "// File Upload Configuration\n";
                    $config .= "define('MAX_FILE_SIZE', 5 * 1024 * 1024);\n";
                    $config .= "define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);\n";
                    $config .= "define('ALLOWED_DOC_TYPES', ['pdf']);\n\n";
                    $config .= "// Timezone\n";
                    $config .= "date_default_timezone_set('Asia/Kolkata');\n\n";
                    $config .= "// Error Reporting (disable in production)\n";
                    $config .= "error_reporting(E_ALL);\n";
                    $config .= "ini_set('display_errors', 1);\n";
                    $config .= "?>";
                    
                    if (file_put_contents('config/config.php', $config)) {
                        $success = 'Database configuration saved successfully!';
                        $step = 2;
                    } else {
                        $error = 'Could not write configuration file. Please check permissions.';
                    }
                }
                mysqli_close($conn);
            }
            break;
            
        case 2:
            // Import database schema
            require_once 'config/config.php';
            
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$conn) {
                $error = 'Database connection failed: ' . mysqli_connect_error();
            } else {
                $sql = file_get_contents('sqlfiles/dpp.sql');
                if (mysqli_multi_query($conn, $sql)) {
                    // Wait for all queries to complete
                    do {
                        if ($result = mysqli_store_result($conn)) {
                            mysqli_free_result($result);
                        }
                    } while (mysqli_next_result($conn));
                    
                    $success = 'Database schema imported successfully!';
                    $step = 3;
                } else {
                    $error = 'Error importing database: ' . mysqli_error($conn);
                }
                mysqli_close($conn);
            }
            break;
            
        case 3:
            // Create admin user
            require_once 'config/config.php';
            require_once 'includes/conn.php';
            
            $adminName = $_POST['admin_name'] ?? 'Administrator';
            $adminEmail = $_POST['admin_email'] ?? 'admin@test.com';
            $adminPassword = $_POST['admin_password'] ?? '123456';
            
            $hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);
            
            $stmt = executeQuery($con, 
                "INSERT INTO admin (name, email, password, type) VALUES (?, ?, ?, 'admin') 
                 ON DUPLICATE KEY UPDATE name = ?, password = ?",
                [$adminName, $adminEmail, $hashedPassword, $adminName, $hashedPassword]
            );
            
            if ($stmt) {
                // Create necessary directories
                $dirs = [
                    'doctor',
                    'patient',
                    'uploads',
                    'logs'
                ];
                
                foreach ($dirs as $dir) {
                    if (!file_exists($dir)) {
                        mkdir($dir, 0755, true);
                    }
                }
                
                // Create installation lock file
                file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
                
                $success = 'Installation completed successfully!';
                $step = 4;
            } else {
                $error = 'Failed to create admin user.';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Patient Portal - Installation</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/modern-dashboard.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .install-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .install-header {
            background: linear-gradient(135deg, #005173 0%, #0066a2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .install-body {
            padding: 2rem;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            background: #e9ecef;
            color: #6c757d;
            font-weight: bold;
        }
        .step.active {
            background: #005173;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-card">
                <div class="install-header">
                    <h2><i class="fas fa-stethoscope"></i> Doctor Patient Portal</h2>
                    <p class="mb-0">Installation Wizard</p>
                </div>
                
                <div class="install-body">
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">1</div>
                        <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">2</div>
                        <div class="step <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : ''; ?>">3</div>
                        <div class="step <?php echo $step >= 4 ? 'active' : ''; ?>">4</div>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($step == 1): ?>
                        <h4>Step 1: Database Configuration</h4>
                        <p>Please enter your database connection details.</p>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label>Database Host</label>
                                <input type="text" class="form-control" name="db_host" value="localhost" required>
                            </div>
                            <div class="form-group">
                                <label>Database Username</label>
                                <input type="text" class="form-control" name="db_user" required>
                            </div>
                            <div class="form-group">
                                <label>Database Password</label>
                                <input type="password" class="form-control" name="db_pass">
                            </div>
                            <div class="form-group">
                                <label>Database Name</label>
                                <input type="text" class="form-control" name="db_name" value="dpp" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Continue</button>
                        </form>
                        
                    <?php elseif ($step == 2): ?>
                        <h4>Step 2: Database Setup</h4>
                        <p>Click continue to import the database schema.</p>
                        
                        <form method="POST">
                            <button type="submit" class="btn btn-primary btn-block">Import Database</button>
                        </form>
                        
                    <?php elseif ($step == 3): ?>
                        <h4>Step 3: Admin Account</h4>
                        <p>Create your administrator account.</p>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label>Admin Name</label>
                                <input type="text" class="form-control" name="admin_name" value="Administrator" required>
                            </div>
                            <div class="form-group">
                                <label>Admin Email</label>
                                <input type="email" class="form-control" name="admin_email" value="admin@test.com" required>
                            </div>
                            <div class="form-group">
                                <label>Admin Password</label>
                                <input type="password" class="form-control" name="admin_password" value="123456" required>
                                <small class="form-text text-muted">Please change this after installation</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Complete Installation</button>
                        </form>
                        
                    <?php elseif ($step == 4): ?>
                        <h4>Installation Complete!</h4>
                        <div class="alert alert-success">
                            <h5>🎉 Congratulations!</h5>
                            <p>Doctor Patient Portal has been installed successfully.</p>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h6>Next Steps:</h6>
                                <ol>
                                    <li>Delete this installation file for security</li>
                                    <li>Run the demo data setup: <code>php setup_demo_data.php</code></li>
                                    <li>Configure your web server</li>
                                    <li>Set up SSL certificate</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-success btn-lg">Go to Application</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/bootstrap.min.js"></script>
</body>
</html>