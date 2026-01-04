<?php

require_once 'config.php';

function getJobs($filters = [], $limit = 10, $offset = 0)
{
    $pdo = getDBConnection();

    $sql = "SELECT j.*, c.name as company_name, c.logo as company_logo, cat.name as category_name 
            FROM jobs j 
            LEFT JOIN companies c ON j.company_id = c.id 
            LEFT JOIN categories cat ON j.category_id = cat.id 
            WHERE j.status = 'active'";

    $params = [];

    if (!empty($filters['keyword'])) {
        $sql .= " AND (j.title LIKE ? OR j.description LIKE ?)";
        $keyword = '%' . $filters['keyword'] . '%';
        $params[] = $keyword;
        $params[] = $keyword;
    }

    if (!empty($filters['location'])) {
        $sql .= " AND j.location LIKE ?";
        $params[] = '%' . $filters['location'] . '%';
    }

    if (!empty($filters['category'])) {
        $sql .= " AND cat.slug = ?";
        $params[] = $filters['category'];
    }

    if (!empty($filters['job_type'])) {
        $sql .= " AND j.job_type = ?";
        $params[] = $filters['job_type'];
    }

    if (!empty($filters['experience'])) {
        $sql .= " AND j.experience_level = ?";
        $params[] = $filters['experience'];
    }

    $sql .= " ORDER BY j.created_at DESC LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getJob($identifier)
{
    $pdo = getDBConnection();

    $field = is_numeric($identifier) ? 'j.id' : 'j.slug';

    $sql = "SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description,
                   c.location as company_location, c.website as company_website, cat.name as category_name,
                   u.name as posted_by
            FROM jobs j 
            LEFT JOIN companies c ON j.company_id = c.id 
            LEFT JOIN categories cat ON j.category_id = cat.id 
            LEFT JOIN users u ON j.user_id = u.id
            WHERE $field = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$identifier]);
    return $stmt->fetch();
}

function incrementJobViews($jobId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE jobs SET views = views + 1 WHERE id = ?");
    $stmt->execute([$jobId]);
}

function getCategories()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT c.*, COUNT(j.id) as job_count 
                         FROM categories c 
                         LEFT JOIN jobs j ON c.id = j.category_id AND j.status = 'active'
                         GROUP BY c.id 
                         ORDER BY c.name");
    return $stmt->fetchAll();
}

function getFeaturedJobs($limit = 6)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT j.*, c.name as company_name, c.logo as company_logo 
                           FROM jobs j 
                           LEFT JOIN companies c ON j.company_id = c.id 
                           WHERE j.status = 'active' 
                           ORDER BY j.views DESC, j.created_at DESC 
                           LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getRecentJobs($limit = 5)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT j.*, c.name as company_name, c.logo as company_logo 
                           FROM jobs j 
                           LEFT JOIN companies c ON j.company_id = c.id 
                           WHERE j.status = 'active' 
                           ORDER BY j.created_at DESC 
                           LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function hasApplied($userId, $jobId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);
    return $stmt->fetch() ? true : false;
}

function applyForJob($userId, $jobId, $coverLetter, $resume = null)
{
    $pdo = getDBConnection();

    try {
        $stmt = $pdo->prepare("INSERT INTO applications (user_id, job_id, cover_letter, resume) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $jobId, $coverLetter, $resume]);
        return ['success' => true, 'message' => 'Application submitted successfully!'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return ['success' => false, 'message' => 'You have already applied for this job.'];
        }
        return ['success' => false, 'message' => 'Error submitting application.'];
    }
}

function getUserApplications($userId, $status = null)
{
    $pdo = getDBConnection();
    $sql = "SELECT a.*, j.title as job_title, j.location as job_location, j.job_type,
                   c.name as company_name, c.logo as company_logo
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN companies c ON j.company_id = c.id
            WHERE a.user_id = ?";

    $params = [$userId];

    if ($status) {
        $sql .= " AND a.status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY a.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function createApplication($userId, $jobId, $data)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'You have already applied for this job'];
    }

    $stmt = $pdo->prepare("INSERT INTO applications (user_id, job_id, cover_letter, resume_path, status, created_at)
                          VALUES (?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)");

    $resumePath = isset($data['resume_path']) ? $data['resume_path'] : null;

    try {
        $stmt->execute([$userId, $jobId, $data['cover_letter'] ?? '', $resumePath]);
        $applicationId = $pdo->lastInsertId();
        return ['success' => true, 'application_id' => $applicationId, 'message' => 'Application submitted successfully'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to submit application: ' . $e->getMessage()];
    }
}

function getJobApplications($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT a.*, j.title as job_title, u.name as applicant_name, 
                                  u.email as applicant_email, u.avatar as applicant_avatar,
                                  u.skills as applicant_skills
                           FROM applications a
                           JOIN jobs j ON a.job_id = j.id
                           JOIN users u ON a.user_id = u.id
                           WHERE j.user_id = ?
                           ORDER BY a.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function getEmployerJobs($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT j.*, c.name as company_name,
                           (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
                           FROM jobs j
                           JOIN companies c ON j.company_id = c.id
                           WHERE j.user_id = ?
                           ORDER BY j.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function toggleSaveJob($userId, $jobId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);

    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
        $stmt->execute([$userId, $jobId]);
        return ['saved' => false, 'message' => 'Job removed from saved list'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
        $stmt->execute([$userId, $jobId]);
        return ['saved' => true, 'message' => 'Job saved successfully'];
    }
}

function getSavedJobs($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT j.*, c.name as company_name, c.logo as company_logo, s.created_at as saved_at
                           FROM saved_jobs s
                           JOIN jobs j ON s.job_id = j.id
                           JOIN companies c ON j.company_id = c.id
                           WHERE s.user_id = ?
                           ORDER BY s.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function isJobSaved($userId, $jobId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);
    return $stmt->fetch() ? true : false;
}

function getUserProfile($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function getCompanyByUser($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function getStats()
{
    $pdo = getDBConnection();

    $stats = [];

    $stmt = $pdo->query("SELECT COUNT(*) FROM jobs WHERE status = 'active'");
    $stats['jobs'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'candidate'");
    $stats['candidates'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM companies");
    $stats['companies'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM applications");
    $stats['applications'] = $stmt->fetchColumn();

    return $stats;
}

function searchJobs($keyword, $location = '')
{
    return getJobs(['keyword' => $keyword, 'location' => $location], 20);
}

function formatSalary($min, $max, $type = 'yearly')
{
    if (!$min && !$max) return 'Negotiable';

    $format = function ($val) {
        if ($val >= 1000) {
            return '$' . number_format($val / 1000, 0) . 'k';
        }
        return '$' . number_format($val);
    };

    if ($min && $max) {
        return $format($min) . ' - ' . $format($max) . '/' . substr($type, 0, 2);
    }

    return $format($min ?: $max) . '/' . substr($type, 0, 2);
}

function saveJob($userId, $jobId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);

    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Job already saved'];
    }

    $stmt = $pdo->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
    $stmt->execute([$userId, $jobId]);
    return ['success' => true, 'message' => 'Job saved successfully'];
}

function unsaveJob($userId, $jobId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);

    if ($stmt->rowCount() > 0) {
        return ['success' => true, 'message' => 'Job removed from saved list'];
    } else {
        return ['success' => false, 'message' => 'Job was not saved'];
    }
}
