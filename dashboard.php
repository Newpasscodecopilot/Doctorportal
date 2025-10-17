<?php
require_once 'config/config.php';
require_once 'includes/Session.php';
require_once 'includes/Security.php';

Session::start();
Security::requireLogin();

$userEmail = Session::get('useremail');
$userType = Session::get('user_type', 'unknown');

// Redirect based on user type
switch($userType) {
    case 'doctor':
        header('Location: doctor.php');
        break;
    case 'patient':
        header('Location: patient.php');
        break;
    case 'admin':
        header('Location: adminpanel.php');
        break;
    default:
        header('Location: index.php');
        break;
}
exit;
?>