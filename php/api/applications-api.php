<?php

/**
 * Applications API
 * Handles application management for candidates
 */

require_once '../includes/config.php';
require_once '../auth/auth.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'list':
                $status = $_GET['status'] ?? null;
                $applications = getUserApplications($userId, $status);
                echo json_encode(['success' => true, 'applications' => $applications]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    case 'POST':
        switch ($action) {
            case 'create':
                // Only candidates can apply
                if ($userType !== 'candidate') {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Only candidates can apply for jobs']);
                    exit();
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $jobId = $data['job_id'] ?? 0;
                $coverLetter = $data['cover_letter'] ?? '';

                if (!$jobId) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Job ID is required']);
                    exit();
                }

                $result = createApplication($userId, $jobId, [
                    'cover_letter' => $coverLetter,
                    'resume_path' => $data['resume_path'] ?? null
                ]);

                if ($result['success']) {
                    echo json_encode($result);
                } else {
                    http_response_code(400);
                    echo json_encode($result);
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
