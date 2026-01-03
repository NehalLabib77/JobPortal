<?php

/**
 * Jobs API
 * RESTful API for job operations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';
require_once '../includes/functions.php';

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
    case 'DELETE':
        handleDeleteRequest($action);
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
        case 'list':
            $filters = [
                'keyword' => $_GET['keyword'] ?? '',
                'location' => $_GET['location'] ?? '',
                'category' => $_GET['category'] ?? '',
                'job_type' => $_GET['job_type'] ?? '',
                'salary_min' => $_GET['salary_min'] ?? null,
                'salary_max' => $_GET['salary_max'] ?? null,
                'limit' => $_GET['limit'] ?? 10,
                'offset' => $_GET['offset'] ?? 0
            ];
            $jobs = getJobs($filters);
            jsonResponse(['success' => true, 'data' => $jobs]);
            break;

        case 'single':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }
            $job = getJob($id);
            if ($job) {
                jsonResponse(['success' => true, 'data' => $job]);
            } else {
                jsonResponse(['error' => 'Job not found'], 404);
            }
            break;

        case 'featured':
            $limit = intval($_GET['limit'] ?? 6);
            $jobs = getFeaturedJobs($limit);
            jsonResponse(['success' => true, 'data' => $jobs]);
            break;

        case 'categories':
            $categories = getCategories();
            jsonResponse(['success' => true, 'data' => $categories]);
            break;

        case 'search':
            $keyword = $_GET['q'] ?? '';
            $location = $_GET['location'] ?? '';
            $jobs = getJobs([
                'keyword' => $keyword,
                'location' => $location,
                'limit' => 20
            ]);
            jsonResponse(['success' => true, 'data' => $jobs]);
            break;

        default:
            // Default to list all active jobs
            $jobs = getJobs(['limit' => 10]);
            jsonResponse(['success' => true, 'data' => $jobs]);
    }
}

/**
 * Handle POST requests
 */
function handlePostRequest($action)
{
    // Check authentication
    if (!isLoggedIn()) {
        jsonResponse(['error' => 'Authentication required'], 401);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    switch ($action) {
        case 'apply':
            if ($_SESSION['user']['role'] !== 'candidate') {
                jsonResponse(['error' => 'Only candidates can apply'], 403);
            }

            $jobId = intval($data['job_id'] ?? 0);
            $coverLetter = $data['cover_letter'] ?? '';

            if (!$jobId) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }

            $result = applyForJob($_SESSION['user']['id'], $jobId, $coverLetter);
            jsonResponse($result, $result['success'] ? 200 : 400);
            break;

        case 'save':
            $jobId = intval($data['job_id'] ?? 0);
            if (!$jobId) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }

            $result = saveJob($_SESSION['user']['id'], $jobId);
            jsonResponse($result);
            break;

        case 'unsave':
            $jobId = intval($data['job_id'] ?? 0);
            if (!$jobId) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }

            $result = unsaveJob($_SESSION['user']['id'], $jobId);
            jsonResponse($result);
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
    if (!isLoggedIn() || $_SESSION['user']['role'] !== 'employer') {
        jsonResponse(['error' => 'Employer authentication required'], 401);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    switch ($action) {
        case 'update':
            $jobId = intval($data['id'] ?? 0);
            if (!$jobId) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }

            require_once '../jobs/jobs.php';
            $result = updateJob($jobId, $data);
            jsonResponse($result);
            break;

        case 'application-status':
            $applicationId = intval($data['application_id'] ?? 0);
            $status = $data['status'] ?? '';

            if (!$applicationId || !$status) {
                jsonResponse(['error' => 'Application ID and status required'], 400);
            }

            require_once '../jobs/jobs.php';
            $result = updateApplicationStatus($applicationId, $status);
            jsonResponse($result);
            break;

        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

/**
 * Handle DELETE requests
 */
function handleDeleteRequest($action)
{
    if (!isLoggedIn() || $_SESSION['user']['role'] !== 'employer') {
        jsonResponse(['error' => 'Employer authentication required'], 401);
    }

    switch ($action) {
        case 'delete':
            $jobId = intval($_GET['id'] ?? 0);
            if (!$jobId) {
                jsonResponse(['error' => 'Job ID required'], 400);
            }

            require_once '../jobs/jobs.php';
            $result = deleteJob($jobId);
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
