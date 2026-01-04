<?php

require_once '../includes/config.php';
require_once '../includes/interview_functions.php';
require_once '../auth/auth.php';

header('Content-Type: application/json');

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
                $interviews = getInterviews($userId, $userType, $status);
                echo json_encode(['success' => true, 'interviews' => $interviews]);
                break;

            case 'details':
                $interviewId = $_GET['id'] ?? 0;
                $interview = getInterview($interviewId, $userId, $userType);
                if ($interview) {
                    echo json_encode(['success' => true, 'interview' => $interview]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Interview not found']);
                }
                break;

            case 'upcoming_count':
                $count = getUpcomingInterviewsCount($userId, $userType);
                echo json_encode(['success' => true, 'count' => $count]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        switch ($action) {
            case 'schedule':
                if ($userType !== 'employer') {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Only employers can schedule interviews']);
                    break;
                }

                $result = scheduleInterview($data);
                if ($result['success']) {
                    http_response_code(201);
                } else {
                    http_response_code(400);
                }
                echo json_encode($result);
                break;

            case 'update':
                $interviewId = $_GET['id'] ?? 0;
                if (!$interviewId) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Interview ID required']);
                    break;
                }

                $interview = getInterview($interviewId, $userId, $userType);
                if (!$interview) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Interview not found or access denied']);
                    break;
                }

                $result = updateInterview($interviewId, $data);
                echo json_encode($result);
                break;

            case 'cancel':
                $interviewId = $_GET['id'] ?? 0;
                if (!$interviewId) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Interview ID required']);
                    break;
                }

                $result = cancelInterview($interviewId, $userId, $userType);
                echo json_encode($result);
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
