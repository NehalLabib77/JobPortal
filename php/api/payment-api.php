<?php

/**
 * JobPortal - Payment API
 * Handles payment-related API requests
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../payments/payment-handler.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        // Create new payment
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = [
            'job_id' => $_POST['job_id'] ?? null,
            'payment_method' => $_POST['payment_method'] ?? '',
            'payer_phone' => $_POST['payer_phone'] ?? '',
            'payer_name' => $_POST['payer_name'] ?? '',
            'payment_reference' => $_POST['payment_reference'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];

        $result = createPayment($data);
        echo json_encode($result);
        break;

    case 'get':
        // Get payment by transaction ID
        $transactionId = $_GET['transaction_id'] ?? '';
        if (empty($transactionId)) {
            echo json_encode(['success' => false, 'message' => 'Transaction ID required']);
            exit;
        }

        $payment = getPaymentByTransaction($transactionId);
        if ($payment) {
            echo json_encode(['success' => true, 'payment' => $payment]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Payment not found']);
        }
        break;

    case 'user_payments':
        // Get payments for logged-in user
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $payments = getUserPayments($_SESSION['user_id']);
        echo json_encode(['success' => true, 'payments' => $payments]);
        break;

    case 'all':
        // Get all payments (admin only)
        if (!isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $filters = [
            'status' => $_GET['status'] ?? '',
            'method' => $_GET['method'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        $payments = getAllPayments($filters);
        echo json_encode(['success' => true, 'payments' => $payments]);
        break;

    case 'update_status':
        // Update payment status (admin only)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $paymentId = $_POST['payment_id'] ?? 0;
        $status = $_POST['status'] ?? '';

        $result = updatePaymentStatus($paymentId, $status);
        echo json_encode($result);
        break;

    case 'stats':
        // Get payment statistics (admin only)
        if (!isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $period = $_GET['period'] ?? 'all';
        $stats = getPaymentStats($period);
        echo json_encode(['success' => true, 'stats' => $stats]);
        break;

    case 'report':
        // Generate payment report (admin only)
        if (!isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $format = $_GET['format'] ?? 'array';

        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="payment_report_' . $startDate . '_' . $endDate . '.csv"');
            echo generatePaymentReport($startDate, $endDate, 'csv');
        } else {
            $report = generatePaymentReport($startDate, $endDate);
            echo json_encode(['success' => true, 'report' => $report]);
        }
        break;

    case 'get_fee':
        // Get current job posting fee
        echo json_encode([
            'success' => true,
            'fee' => JOB_POSTING_FEE,
            'currency' => 'BDT',
            'methods' => [
                'bkash' => ['name' => 'bKash', 'number' => '01712345678', 'color' => '#E2136E'],
                'nagad' => ['name' => 'Nagad', 'number' => '01812345678', 'color' => '#F6A21E'],
                'rocket' => ['name' => 'Rocket', 'number' => '01912345678', 'color' => '#8C3494']
            ]
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
