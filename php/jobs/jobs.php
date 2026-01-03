<?php

/**
 * JobPortal - Job Management Functions
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Create new job
function createJob($data)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $pdo = getDBConnection();

    // Get company
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $company = $stmt->fetch();

    if (!$company) {
        return ['success' => false, 'message' => 'Please create a company profile first.'];
    }

    // Validate required fields
    $required = ['title', 'description', 'job_type', 'location'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => ucfirst($field) . ' is required.'];
        }
    }

    // Generate slug
    $slug = generateSlug($data['title']) . '-' . time();

    // Set initial status (draft if payment required, active otherwise)
    $status = isset($data['status']) ? $data['status'] : 'draft';

    $sql = "INSERT INTO jobs (company_id, user_id, category_id, title, slug, description, requirements, 
            benefits, job_type, experience_level, salary_min, salary_max, salary_type, location, 
            is_remote, vacancies, deadline, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $company['id'],
            $_SESSION['user_id'],
            $data['category_id'] ?: null,
            sanitize($data['title']),
            $slug,
            $data['description'],
            $data['requirements'] ?? '',
            $data['benefits'] ?? '',
            $data['job_type'],
            $data['experience_level'] ?? 'entry',
            $data['salary_min'] ?: null,
            $data['salary_max'] ?: null,
            $data['salary_type'] ?? 'yearly',
            sanitize($data['location']),
            isset($data['is_remote']) ? 1 : 0,
            $data['vacancies'] ?? 1,
            $data['deadline'] ?: null,
            $status
        ]);

        return ['success' => true, 'message' => 'Job posted successfully!', 'job_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to post job. ' . $e->getMessage()];
    }
}

// Update job
function updateJob($jobId, $data)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $pdo = getDBConnection();

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM jobs WHERE id = ? AND user_id = ?");
    $stmt->execute([$jobId, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        return ['success' => false, 'message' => 'Job not found or unauthorized.'];
    }

    $allowedFields = [
        'title',
        'description',
        'requirements',
        'benefits',
        'job_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_type',
        'location',
        'is_remote',
        'vacancies',
        'deadline',
        'status',
        'category_id'
    ];

    $updates = [];
    $params = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields)) {
            $updates[] = "$key = ?";
            $params[] = $value;
        }
    }

    if (empty($updates)) {
        return ['success' => false, 'message' => 'No valid fields to update.'];
    }

    $params[] = $jobId;
    $sql = "UPDATE jobs SET " . implode(', ', $updates) . " WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => 'Job updated successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update job.'];
    }
}

// Delete job
function deleteJob($jobId)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $pdo = getDBConnection();

    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM jobs WHERE id = ? AND user_id = ?");
    $stmt->execute([$jobId, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        return ['success' => false, 'message' => 'Job not found or unauthorized.'];
    }

    $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->execute([$jobId]);

    return ['success' => true, 'message' => 'Job deleted successfully.'];
}

// Update application status
function updateApplicationStatus($applicationId, $status)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $validStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
    if (!in_array($status, $validStatuses)) {
        return ['success' => false, 'message' => 'Invalid status.'];
    }

    $pdo = getDBConnection();

    // Verify the application belongs to employer's job
    $stmt = $pdo->prepare("SELECT a.id FROM applications a 
                           JOIN jobs j ON a.job_id = j.id 
                           WHERE a.id = ? AND j.user_id = ?");
    $stmt->execute([$applicationId, $_SESSION['user_id']]);

    if (!$stmt->fetch()) {
        return ['success' => false, 'message' => 'Application not found.'];
    }

    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $applicationId]);

    return ['success' => true, 'message' => 'Application status updated.'];
}

// Create/Update Company Profile
function saveCompanyProfile($data)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $pdo = getDBConnection();
    $userId = $_SESSION['user_id'];

    // Check if company exists
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update
        $sql = "UPDATE companies SET name = ?, description = ?, industry = ?, company_size = ?, 
                founded_year = ?, website = ?, location = ?, email = ?, phone = ? WHERE user_id = ?";
        $params = [
            sanitize($data['name']),
            $data['description'],
            $data['industry'] ?? '',
            $data['company_size'] ?? '',
            $data['founded_year'] ?: null,
            $data['website'] ?? '',
            sanitize($data['location'] ?? ''),
            $data['email'] ?? '',
            $data['phone'] ?? '',
            $userId
        ];
    } else {
        // Insert
        $slug = generateSlug($data['name']) . '-' . $userId;
        $sql = "INSERT INTO companies (user_id, name, slug, description, industry, company_size, 
                founded_year, website, location, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $userId,
            sanitize($data['name']),
            $slug,
            $data['description'],
            $data['industry'] ?? '',
            $data['company_size'] ?? '',
            $data['founded_year'] ?: null,
            $data['website'] ?? '',
            sanitize($data['location'] ?? ''),
            $data['email'] ?? '',
            $data['phone'] ?? ''
        ];
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => 'Company profile saved!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to save company profile.'];
    }
}
