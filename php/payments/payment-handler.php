<?php

/**
 * JobPortal - Payment Handler Functions
 * Handles payment processing for job postings (200 TK fee)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Job posting fee in BDT
define('JOB_POSTING_FEE', 200);

/**
 * Create a new payment record
 */
function createPayment($data)
{
    if (!isLoggedIn() || !isEmployer()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $pdo = getDBConnection();

    // Validate required fields
    $required = ['payment_method', 'payer_phone', 'payment_reference', 'payer_name'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.'];
        }
    }

    // Validate payment method
    $validMethods = ['bkash', 'nagad', 'rocket', 'card', 'bank'];
    if (!in_array($data['payment_method'], $validMethods)) {
        return ['success' => false, 'message' => 'Invalid payment method.'];
    }

    // Generate unique transaction ID
    $transactionId = 'TXN' . date('YmdHis') . rand(1000, 9999);

    $sql = "INSERT INTO payments (user_id, job_id, transaction_id, amount, currency, payment_method, 
            payment_status, payer_phone, payer_name, payment_reference, notes) 
            VALUES (?, ?, ?, ?, 'BDT', ?, 'pending', ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_SESSION['user_id'],
            $data['job_id'] ?? null,
            $transactionId,
            JOB_POSTING_FEE,
            $data['payment_method'],
            sanitize($data['payer_phone']),
            sanitize($data['payer_name']),
            sanitize($data['payment_reference']),
            $data['notes'] ?? ''
        ]);

        return [
            'success' => true,
            'message' => 'Payment submitted successfully! Awaiting verification.',
            'transaction_id' => $transactionId,
            'payment_id' => $pdo->lastInsertId()
        ];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to process payment. ' . $e->getMessage()];
    }
}

/**
 * Get payment by transaction ID
 */
function getPaymentByTransaction($transactionId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("
        SELECT p.*, u.name as user_name, u.email as user_email, j.title as job_title, c.name as company_name
        FROM payments p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN jobs j ON p.job_id = j.id
        LEFT JOIN companies c ON j.company_id = c.id
        WHERE p.transaction_id = ?
    ");
    $stmt->execute([$transactionId]);
    return $stmt->fetch();
}

/**
 * Get payments for a specific user (employer)
 */
function getUserPayments($userId, $limit = 50)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("
        SELECT p.*, j.title as job_title
        FROM payments p
        LEFT JOIN jobs j ON p.job_id = j.id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll();
}

/**
 * Get all payments (admin)
 */
function getAllPayments($filters = [], $limit = 100)
{
    if (!isAdmin()) {
        return [];
    }

    $pdo = getDBConnection();
    $where = [];
    $params = [];

    if (!empty($filters['status'])) {
        $where[] = "p.payment_status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['method'])) {
        $where[] = "p.payment_method = ?";
        $params[] = $filters['method'];
    }

    if (!empty($filters['start_date'])) {
        $where[] = "DATE(p.created_at) >= ?";
        $params[] = $filters['start_date'];
    }

    if (!empty($filters['end_date'])) {
        $where[] = "DATE(p.created_at) <= ?";
        $params[] = $filters['end_date'];
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql = "
        SELECT p.*, u.name as user_name, u.email as user_email, 
               j.title as job_title, c.name as company_name
        FROM payments p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN jobs j ON p.job_id = j.id
        LEFT JOIN companies c ON j.company_id = c.id
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT $limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Update payment status (admin)
 */
function updatePaymentStatus($paymentId, $status)
{
    if (!isAdmin()) {
        return ['success' => false, 'message' => 'Unauthorized'];
    }

    $validStatuses = ['pending', 'completed', 'failed', 'refunded'];
    if (!in_array($status, $validStatuses)) {
        return ['success' => false, 'message' => 'Invalid status'];
    }

    $pdo = getDBConnection();

    try {
        $stmt = $pdo->prepare("UPDATE payments SET payment_status = ? WHERE id = ?");
        $stmt->execute([$status, $paymentId]);

        // If completed, activate the associated job
        if ($status === 'completed') {
            $stmt = $pdo->prepare("SELECT job_id FROM payments WHERE id = ?");
            $stmt->execute([$paymentId]);
            $payment = $stmt->fetch();

            if ($payment && $payment['job_id']) {
                $stmt = $pdo->prepare("UPDATE jobs SET status = 'active' WHERE id = ?");
                $stmt->execute([$payment['job_id']]);
            }
        }

        return ['success' => true, 'message' => 'Payment status updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update payment status'];
    }
}

/**
 * Get payment statistics (admin)
 */
function getPaymentStats($period = 'all')
{
    if (!isAdmin()) {
        return [];
    }

    $pdo = getDBConnection();

    $dateFilter = '';
    switch ($period) {
        case 'today':
            $dateFilter = "AND DATE(created_at) = CURDATE()";
            break;
        case 'week':
            $dateFilter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $dateFilter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            break;
        case 'year':
            $dateFilter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }

    $stats = [];

    // Total earnings (completed payments)
    $stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE payment_status = 'completed' $dateFilter");
    $stats['total_earnings'] = $stmt->fetch()['total'];

    // Total payments count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payments WHERE 1=1 $dateFilter");
    $stats['total_payments'] = $stmt->fetch()['count'];

    // Completed payments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payments WHERE payment_status = 'completed' $dateFilter");
    $stats['completed_payments'] = $stmt->fetch()['count'];

    // Pending payments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payments WHERE payment_status = 'pending' $dateFilter");
    $stats['pending_payments'] = $stmt->fetch()['count'];

    // By payment method
    $stmt = $pdo->query("
        SELECT payment_method, COUNT(*) as count, SUM(CASE WHEN payment_status = 'completed' THEN amount ELSE 0 END) as total
        FROM payments 
        WHERE 1=1 $dateFilter
        GROUP BY payment_method
    ");
    $stats['by_method'] = $stmt->fetchAll();

    return $stats;
}

/**
 * Generate payment report (admin)
 */
function generatePaymentReport($startDate, $endDate, $format = 'array')
{
    if (!isAdmin()) {
        return [];
    }

    $pdo = getDBConnection();

    $sql = "
        SELECT 
            p.transaction_id,
            p.created_at as payment_date,
            u.name as employer_name,
            u.email as employer_email,
            c.name as company_name,
            j.title as job_title,
            p.payment_method,
            p.payer_phone,
            p.payer_name,
            p.payment_reference,
            p.amount,
            p.currency,
            p.payment_status
        FROM payments p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN jobs j ON p.job_id = j.id
        LEFT JOIN companies c ON j.company_id = c.id
        WHERE DATE(p.created_at) BETWEEN ? AND ?
        ORDER BY p.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$startDate, $endDate]);
    $data = $stmt->fetchAll();

    if ($format === 'csv') {
        return generateCSV($data);
    }

    return $data;
}

/**
 * Generate CSV from data
 */
function generateCSV($data)
{
    if (empty($data)) {
        return '';
    }

    $output = fopen('php://temp', 'r+');

    // Headers
    fputcsv($output, array_keys($data[0]));

    // Data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);

    return $csv;
}

/**
 * Get payment method display info
 */
function getPaymentMethodInfo($method)
{
    $methods = [
        'bkash' => [
            'name' => 'bKash',
            'color' => '#E2136E',
            'number' => '01712345678'
        ],
        'nagad' => [
            'name' => 'Nagad',
            'color' => '#F6A21E',
            'number' => '01812345678'
        ],
        'rocket' => [
            'name' => 'Rocket',
            'color' => '#8C3494',
            'number' => '01912345678'
        ],
        'card' => [
            'name' => 'Card',
            'color' => '#1a73e8',
            'number' => ''
        ],
        'bank' => [
            'name' => 'Bank Transfer',
            'color' => '#34a853',
            'number' => ''
        ]
    ];

    return $methods[$method] ?? ['name' => ucfirst($method), 'color' => '#666', 'number' => ''];
}
