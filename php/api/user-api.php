<?php

/**
 * User API
 * RESTful API for user operations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';
require_once '../auth/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        handleGetRequest($action);
        break;
    case 'POST':
        handlePostRequest($action);
        break;
    case 'PUT':
        handlePutRequest($action);
        break;
    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}

/**
 * Handle GET requests
 */
function handleGetRequest($action)
{
    switch ($action) {
        case 'profile':
            if (!isLoggedIn()) {
                jsonResponse(['error' => 'Authentication required'], 401);
            }

            global $pdo;
            $stmt = $pdo->prepare("SELECT id, name, email, role, avatar, phone, location, bio, website, skills, resume, created_at FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user']['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                jsonResponse(['success' => true, 'data' => $user]);
            } else {
                jsonResponse(['error' => 'User not found'], 404);
            }
            break;

        case 'applications':
            if (!isLoggedIn()) {
                jsonResponse(['error' => 'Authentication required'], 401);
            }

            require_once '../includes/functions.php';
            $applications = getUserApplications($_SESSION['user']['id']);
            jsonResponse(['success' => true, 'data' => $applications]);
            break;

        case 'saved-jobs':
            if (!isLoggedIn()) {
                jsonResponse(['error' => 'Authentication required'], 401);
            }

            require_once '../includes/functions.php';
            $savedJobs = getSavedJobs($_SESSION['user']['id']);
            jsonResponse(['success' => true, 'data' => $savedJobs]);
            break;

        case 'check-auth':
            if (isLoggedIn()) {
                jsonResponse([
                    'success' => true,
                    'authenticated' => true,
                    'user' => [
                        'id' => $_SESSION['user']['id'],
                        'name' => $_SESSION['user']['name'],
                        'email' => $_SESSION['user']['email'],
                        'role' => $_SESSION['user']['role']
                    ]
                ]);
            } else {
                jsonResponse([
                    'success' => true,
                    'authenticated' => false
                ]);
            }
            break;

        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

/**
 * Handle POST requests
 */
function handlePostRequest($action)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // For form data
    if (empty($data)) {
        $data = $_POST;
    }

    switch ($action) {
        case 'login':
            $email = sanitize($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if (empty($email) || empty($password)) {
                jsonResponse(['error' => 'Email and password required'], 400);
            }

            $result = loginUser($email, $password);
            jsonResponse($result, $result['success'] ? 200 : 401);
            break;

        case 'register':
            $name = sanitize($data['name'] ?? '');
            $email = sanitize($data['email'] ?? '');
            $password = $data['password'] ?? '';
            $role = sanitize($data['role'] ?? 'candidate');

            if (empty($name) || empty($email) || empty($password)) {
                jsonResponse(['error' => 'All fields are required'], 400);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                jsonResponse(['error' => 'Invalid email format'], 400);
            }

            if (strlen($password) < 6) {
                jsonResponse(['error' => 'Password must be at least 6 characters'], 400);
            }

            $result = registerUser($name, $email, $password, $role);
            jsonResponse($result, $result['success'] ? 201 : 400);
            break;

        case 'logout':
            logoutUser();
            jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
            break;

        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

/**
 * Handle PUT requests (Update)
 */
function handlePutRequest($action)
{
    if (!isLoggedIn()) {
        jsonResponse(['error' => 'Authentication required'], 401);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    switch ($action) {
        case 'profile':
            $result = updateProfile($_SESSION['user']['id'], $data);
            jsonResponse($result);
            break;

        case 'password':
            $currentPassword = $data['current_password'] ?? '';
            $newPassword = $data['new_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword)) {
                jsonResponse(['error' => 'Current and new password required'], 400);
            }

            $result = changePassword($_SESSION['user']['id'], $currentPassword, $newPassword);
            jsonResponse($result);
            break;

        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}
