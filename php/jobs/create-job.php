<?php

require_once '../includes/config.php';
require_once '../jobs/jobs.php';
require_once '../payments/payment-handler.php';

if (!isLoggedIn() || !isEmployer()) {
    $_SESSION['error'] = 'Please login as an employer to post jobs.';
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $payerPhone = $_POST['payer_phone'] ?? '';
    $payerName = $_POST['payer_name'] ?? '';
    $paymentReference = $_POST['payment_reference'] ?? '';

    if (empty($paymentMethod) || empty($payerPhone) || empty($payerName) || empty($paymentReference)) {
        $_SESSION['error'] = 'Payment details are required to post a job.';
        redirect('../post-job.html');
    }

    $jobData = [
        'title' => $_POST['title'] ?? '',
        'category_id' => $_POST['category_id'] ?? null,
        'job_type' => $_POST['job_type'] ?? 'full-time',
        'experience_level' => $_POST['experience_level'] ?? 'entry',
        'vacancies' => $_POST['vacancies'] ?? 1,
        'description' => $_POST['description'] ?? '',
        'requirements' => $_POST['requirements'] ?? '',
        'benefits' => $_POST['benefits'] ?? '',
        'location' => $_POST['location'] ?? '',
        'is_remote' => isset($_POST['is_remote']),
        'salary_min' => $_POST['salary_min'] ?: null,
        'salary_max' => $_POST['salary_max'] ?: null,
        'salary_type' => $_POST['salary_type'] ?? 'monthly',
        'deadline' => $_POST['deadline'] ?: null,
        'status' => 'draft' // Will be activated after payment verification
    ];

    $jobResult = createJob($jobData);

    if (!$jobResult['success']) {
        $_SESSION['error'] = $jobResult['message'];
        redirect('../post-job.html');
    }

    $jobId = $jobResult['job_id'];

    $paymentData = [
        'job_id' => $jobId,
        'payment_method' => $paymentMethod,
        'payer_phone' => $payerPhone,
        'payer_name' => $payerName,
        'payment_reference' => $paymentReference,
        'notes' => 'Job posting fee for: ' . $jobData['title']
    ];

    $paymentResult = createPayment($paymentData);

    if ($paymentResult['success']) {
        $_SESSION['success'] = 'Job submitted successfully! Your payment is being verified. Transaction ID: ' . $paymentResult['transaction_id'];
        $_SESSION['transaction_id'] = $paymentResult['transaction_id'];
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->execute([$jobId]);

        $_SESSION['error'] = 'Payment processing failed: ' . $paymentResult['message'];
        redirect('../post-job.html');
    }

    redirect('../dashboard-employer.php');
} else {
    redirect('../post-job.html');
}
