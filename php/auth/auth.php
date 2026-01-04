<?php

require_once __DIR__ . '/../includes/config.php';

function registerUser($data)
{
    $pdo = getDBConnection();

    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        return ['success' => false, 'message' => 'All fields are required.'];
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format.'];
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email already registered.'];
    }

    if (strlen($data['password']) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
    $userType = isset($data['user_type']) && in_array($data['user_type'], ['candidate', 'employer'])
        ? $data['user_type'] : 'candidate';

    try {
        $stmt->execute([$data['name'], $data['email'], $hashedPassword, $userType]);
        $userId = $pdo->lastInsertId();

        if ($userType === 'employer' && !empty($data['company_name'])) {
            $companySlug = generateSlug($data['company_name']) . '-' . $userId;
            $stmt = $pdo->prepare("INSERT INTO companies (user_id, name, slug) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $data['company_name'], $companySlug]);
        }

        return ['success' => true, 'message' => 'Registration successful! You can now login.', 'user_id' => $userId];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
}

function loginUser($email, $password)
{
    $pdo = getDBConnection();

    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required.'];
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['user_avatar'] = $user['avatar'];

    return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
}

function logoutUser()
{
    session_destroy();
    return ['success' => true, 'message' => 'Logged out successfully.'];
}

function updateProfile($userId, $data)
{
    $pdo = getDBConnection();

    $allowedFields = ['name', 'phone', 'bio', 'location', 'website', 'linkedin', 'skills', 'experience', 'education'];
    $updates = [];
    $params = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields)) {
            $updates[] = "$key = ?";
            $params[] = sanitize($value);
        }
    }

    if (empty($updates)) {
        return ['success' => false, 'message' => 'No valid fields to update.'];
    }

    $params[] = $userId;
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if (isset($data['name'])) {
            $_SESSION['user_name'] = $data['name'];
        }

        return ['success' => true, 'message' => 'Profile updated successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update profile.'];
    }
}

function updateAvatar($userId, $file)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error.'];
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF allowed.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large. Max 2MB allowed.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
    $uploadPath = UPLOAD_PATH . 'avatars/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->execute([$filename, $userId]);
        $_SESSION['user_avatar'] = $filename;

        return ['success' => true, 'message' => 'Avatar updated!', 'filename' => $filename];
    }

    return ['success' => false, 'message' => 'Failed to upload file.'];
}

function changePassword($userId, $currentPassword, $newPassword)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!password_verify($currentPassword, $user['password'])) {
        return ['success' => false, 'message' => 'Current password is incorrect.'];
    }

    if (strlen($newPassword) < 6) {
        return ['success' => false, 'message' => 'New password must be at least 6 characters.'];
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $userId]);

    return ['success' => true, 'message' => 'Password changed successfully!'];
}
