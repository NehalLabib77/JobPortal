<?php

require_once 'config.php';

function getInterviews($userId, $userType, $status = null)
{
    $pdo = getDBConnection();

    if ($userType === 'candidate') {
        $sql = "SELECT i.*, j.title as job_title, j.location, c.name as company_name,
                       u.name as interviewer_name, u.email as interviewer_email
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN companies c ON j.company_id = c.id
                JOIN users u ON i.interviewer_id = u.id
                WHERE i.candidate_id = ?";
    } else {
        $sql = "SELECT i.*, j.title as job_title, j.location,
                       u.name as candidate_name, u.email as candidate_email
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN users u ON i.candidate_id = u.id
                WHERE j.user_id = ?";
    }

    $params = [$userId];

    if ($status) {
        $sql .= " AND i.status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY i.interview_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function scheduleInterview($data)
{
    $pdo = getDBConnection();

    $required = ['application_id', 'interviewer_id', 'candidate_id', 'job_id', 'interview_date'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.'];
        }
    }

    $stmt = $pdo->prepare("SELECT id FROM interviews WHERE application_id = ?");
    $stmt->execute([$data['application_id']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Interview already scheduled for this application.'];
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO interviews
            (application_id, interviewer_id, candidate_id, job_id, interview_date,
             interview_type, platform, meeting_link, notes, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['application_id'],
            $data['interviewer_id'],
            $data['candidate_id'],
            $data['job_id'],
            $data['interview_date'],
            $data['interview_type'] ?? 'video',
            $data['platform'] ?? null,
            $data['meeting_link'] ?? null,
            $data['notes'] ?? null,
            'scheduled'
        ]);

        return ['success' => true, 'message' => 'Interview scheduled successfully!', 'interview_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to schedule interview.'];
    }
}

function updateInterview($interviewId, $data)
{
    $pdo = getDBConnection();

    $allowedFields = ['status', 'feedback', 'rating', 'notes', 'interview_date', 'platform', 'meeting_link'];
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

    $params[] = $interviewId;
    $sql = "UPDATE interviews SET " . implode(', ', $updates) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => 'Interview updated successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update interview.'];
    }
}

function getInterview($interviewId, $userId, $userType)
{
    $pdo = getDBConnection();

    if ($userType === 'candidate') {
        $sql = "SELECT i.*, j.title as job_title, j.location, c.name as company_name,
                       u.name as interviewer_name, u.email as interviewer_email
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN companies c ON j.company_id = c.id
                JOIN users u ON i.interviewer_id = u.id
                WHERE i.id = ? AND i.candidate_id = ?";
    } else {
        $sql = "SELECT i.*, j.title as job_title, j.location,
                       u.name as candidate_name, u.email as candidate_email
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN users u ON i.candidate_id = u.id
                WHERE i.id = ? AND j.user_id = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$interviewId, $userId]);
    return $stmt->fetch();
}

function cancelInterview($interviewId, $userId, $userType)
{
    $pdo = getDBConnection();

    $interview = getInterview($interviewId, $userId, $userType);
    if (!$interview) {
        return ['success' => false, 'message' => 'Interview not found or access denied.'];
    }

    return updateInterview($interviewId, ['status' => 'cancelled']);
}

function getUpcomingInterviewsCount($userId, $userType)
{
    $pdo = getDBConnection();

    if ($userType === 'candidate') {
        $sql = "SELECT COUNT(*) as count FROM interviews
                WHERE candidate_id = ? AND status = 'scheduled'
                AND interview_date > CURRENT_TIMESTAMP";
    } else {
        $sql = "SELECT COUNT(*) as count FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                WHERE j.user_id = ? AND i.status = 'scheduled'
                AND i.interview_date > CURRENT_TIMESTAMP";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['count'];
}
