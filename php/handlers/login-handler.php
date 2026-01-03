<?php

/**
 * Login Handler
 * Processes login form submissions
 */

require_once '../includes/config.php';
require_once '../auth/auth.php';

// Check if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('../../dashboard-admin.php');
    } elseif (isEmployer()) {
        redirect('../../dashboard-employer.php');
    } else {
        redirect('../../dashboard-candidate.php');
    }
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        redirect('../../login.php');
    }

    // Attempt login
    $result = loginUser($email, $password);

    if ($result['success']) {
        // Set remember me cookie if checked
        if ($remember) {
            setcookie('remember_email', $email, time() + (86400 * 30), '/'); // 30 days
        }

        // Check for redirect parameter
        $redirectUrl = $_GET['redirect'] ?? '';
        if (!empty($redirectUrl) && filter_var($redirectUrl, FILTER_VALIDATE_URL) === false && !preg_match('/^https?:\/\//', $redirectUrl)) {
            // Local redirect, safe
            redirect('../../' . $redirectUrl);
        } else {
            // Redirect based on user type
            if (isAdmin()) {
                redirect('../../dashboard-admin.php');
            } elseif (isEmployer()) {
                redirect('../../dashboard-employer.php');
            } else {
                redirect('../../dashboard-candidate.php');
            }
        }
    } else {
        $_SESSION['error'] = $result['message'];
        redirect('../../login.php');
    }
} else {
    redirect('../login.php');
}
