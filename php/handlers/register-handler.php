<?php

/**
 * Register Handler
 * Processes registration form submissions
 */

require_once '../includes/config.php';
require_once '../auth/auth.php';

// Check if already logged in
if (isLoggedIn()) {
    $redirect = $_SESSION['user']['role'] === 'employer' ? 'dashboard-employer.php' : 'dashboard-candidate.php';
    redirect('../' . $redirect);
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role = sanitize($_POST['role'] ?? 'candidate');

    // Validate inputs
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Passwords do not match';
    }

    if (!in_array($role, ['candidate', 'employer'])) {
        $errors[] = 'Invalid role selected';
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        redirect('../register.html');
    }

    // Attempt registration
    $result = registerUser($name, $email, $password, $role);

    if ($result['success']) {
        $_SESSION['success'] = 'Registration successful! Please login.';
        redirect('../login.php');
    } else {
        $_SESSION['error'] = $result['message'];
        redirect('../register.html');
    }
} else {
    redirect('../register.html');
}
