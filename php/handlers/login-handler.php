<?php

require_once '../includes/config.php';
require_once '../auth/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('../../dashboard-admin.php');
    } elseif (isEmployer()) {
        redirect('../../dashboard-employer.php');
    } else {
        redirect('../../dashboard-candidate.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        redirect('../../login.php');
    }

    $result = loginUser($email, $password);

    if ($result['success']) {
        if ($remember) {
            setcookie('remember_email', $email, time() + (86400 * 30), '/');
        }

        $redirectUrl = $_GET['redirect'] ?? '';
        if (!empty($redirectUrl) && filter_var($redirectUrl, FILTER_VALIDATE_URL) === false && !preg_match('/^https?:\/\//', $redirectUrl)) {
            redirect('../../' . $redirectUrl);
        } else {
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
