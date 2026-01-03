<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Admin';
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JobPortal</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="logo">Job<span>Portal</span></a>

                <ul class="nav-menu">
                    <li><a href="index.html" class="nav-link">Home</a></li>
                    <li><a href="jobs.php" class="nav-link">Browse Jobs</a></li>
                </ul>

                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="nav-actions">
                    <span style="color: var(--gray-600); margin-right: 15px;">
                        <i class="fas fa-user-shield"></i> Admin
                    </span>
                    <a href="php/handlers/logout-handler.php" class="btn btn-outline btn-sm">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Dashboard -->
    <section class="dashboard">
        <div class="container">
            <div class="dashboard-container">
                <!-- Sidebar -->
                <aside class="dashboard-sidebar">
                    <div
                        style="text-align: center; padding-bottom: 20px; border-bottom: 1px solid var(--gray-200); margin-bottom: 20px;">
                        <div
                            style="width: 80px; height: 80px; border-radius: 50%; background: var(--danger-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4 style="margin-bottom: 5px;">Admin Panel</h4>
                        <p style="color: var(--gray-500); font-size: 14px; margin: 0;">Administrator</p>
                    </div>

                    <nav class="sidebar-nav">
                        <a href="#" class="active" onclick="showTab('overview')">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a href="#" onclick="showTab('payments')">
                            <i class="fas fa-money-bill-wave"></i> Payments
                            <span class="badge badge-success" style="margin-left: auto;">৳</span>
                        </a>
                        <a href="#" onclick="showTab('reports')">
                            <i class="fas fa-chart-bar"></i> Payment Reports
                        </a>
                        <a href="#" onclick="showTab('users')">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <a href="#" onclick="showTab('jobs')">
                            <i class="fas fa-briefcase"></i> Jobs
                        </a>
                        <a href="#" onclick="showTab('companies')">
                            <i class="fas fa-building"></i> Companies
                        </a>
                        <a href="#" onclick="showTab('settings')">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <a href="php/handlers/logout-handler.php" style="color: var(--danger-color);">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="dashboard-content">
                    <!-- Overview Tab -->
                    <div id="overview-tab">
                        <div class="dashboard-header">
                            <h2>Admin Dashboard</h2>
                            <span style="color: var(--gray-500);">Welcome, Admin!</span>
                        </div>

                        <!-- Stats -->
                        <div class="dashboard-stats">
                            <div class="stat-card" style="border-left: 4px solid var(--success-color);">
                                <i class="fas fa-money-bill-wave" style="color: var(--success-color);"></i>
                                <h3>৳<span id="totalEarnings">8,400</span></h3>
                                <p>Total Earnings</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
                                <i class="fas fa-receipt" style="color: var(--primary-color);"></i>
                                <h3 id="totalPayments">42</h3>
                                <p>Total Payments</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--warning-color);">
                                <i class="fas fa-briefcase" style="color: var(--warning-color);"></i>
                                <h3 id="totalJobs">38</h3>
                                <p>Active Jobs</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--info-color);">
                                <i class="fas fa-users" style="color: var(--info-color);"></i>
                                <h3 id="totalUsers">156</h3>
                                <p>Total Users</p>
                            </div>
                        </div>

                        <!-- Monthly Stats -->
                        <div
                            style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                            <div class="card" style="text-align: center; padding: 25px;">
                                <h4 style="color: var(--gray-500); margin-bottom: 10px;">This Month</h4>
                                <h2 style="color: var(--success-color);">৳3,200</h2>
                                <p style="color: var(--gray-500); margin: 0;">16 payments</p>
                            </div>
                            <div class="card" style="text-align: center; padding: 25px;">
                                <h4 style="color: var(--gray-500); margin-bottom: 10px;">Last Month</h4>
                                <h2 style="color: var(--primary-color);">৳2,800</h2>
                                <p style="color: var(--gray-500); margin: 0;">14 payments</p>
                            </div>
                            <div class="card" style="text-align: center; padding: 25px;">
                                <h4 style="color: var(--gray-500); margin-bottom: 10px;">Pending</h4>
                                <h2 style="color: var(--warning-color);">৳600</h2>
                                <p style="color: var(--gray-500); margin: 0;">3 payments</p>
                            </div>
                        </div>

                        <!-- Recent Payments -->
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-money-bill-wave"></i> Recent Payments</h4>
                                <a href="#" onclick="showTab('payments')">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Employer</th>
                                            <th>Job Title</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>TXN202512290001</code></td>
                                            <td>Tech Solutions Inc</td>
                                            <td>Senior PHP Developer</td>
                                            <td><span class="badge"
                                                    style="background: #E2136E; color: white;">bKash</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>Dec 29, 2025</td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512280002</code></td>
                                            <td>Digital Agency BD</td>
                                            <td>UI/UX Designer</td>
                                            <td><span class="badge"
                                                    style="background: #F6A21E; color: white;">Nagad</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>Dec 28, 2025</td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512280003</code></td>
                                            <td>Software House</td>
                                            <td>Full Stack Developer</td>
                                            <td><span class="badge"
                                                    style="background: #8C3494; color: white;">Rocket</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>Dec 28, 2025</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payments Tab -->
                    <div id="payments-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Payment Management</h2>
                            <div style="display: flex; gap: 10px;">
                                <select class="form-control" style="width: auto;" id="paymentStatusFilter">
                                    <option value="">All Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <select class="form-control" style="width: auto;" id="paymentMethodFilter">
                                    <option value="">All Methods</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Employer</th>
                                        <th>Phone</th>
                                        <th>Job Title</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>TXN202512290001</code></td>
                                        <td>Tech Solutions Inc</td>
                                        <td>01712345678</td>
                                        <td>Senior PHP Developer</td>
                                        <td><span class="badge" style="background: #E2136E; color: white;">bKash</span>
                                        </td>
                                        <td><strong>৳200</strong></td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                        <td>Dec 29, 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="viewPayment('TXN202512290001')"><i
                                                    class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><code>TXN202512280002</code></td>
                                        <td>Digital Agency BD</td>
                                        <td>01823456789</td>
                                        <td>UI/UX Designer</td>
                                        <td><span class="badge" style="background: #F6A21E; color: white;">Nagad</span>
                                        </td>
                                        <td><strong>৳200</strong></td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                        <td>Dec 28, 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><code>TXN202512280003</code></td>
                                        <td>Software House</td>
                                        <td>01934567890</td>
                                        <td>Full Stack Developer</td>
                                        <td><span class="badge" style="background: #8C3494; color: white;">Rocket</span>
                                        </td>
                                        <td><strong>৳200</strong></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>Dec 28, 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-success"
                                                onclick="confirmPayment('TXN202512280003')"><i
                                                    class="fas fa-check"></i></button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="rejectPayment('TXN202512280003')"><i
                                                    class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><code>TXN202512270004</code></td>
                                        <td>StartUp BD</td>
                                        <td>01645678901</td>
                                        <td>Marketing Manager</td>
                                        <td><span class="badge" style="background: #E2136E; color: white;">bKash</span>
                                        </td>
                                        <td><strong>৳200</strong></td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                        <td>Dec 27, 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><code>TXN202512270005</code></td>
                                        <td>E-Commerce Ltd</td>
                                        <td>01756789012</td>
                                        <td>Sales Executive</td>
                                        <td><span class="badge" style="background: #F6A21E; color: white;">Nagad</span>
                                        </td>
                                        <td><strong>৳200</strong></td>
                                        <td><span class="badge badge-danger">Failed</span></td>
                                        <td>Dec 27, 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-warning"><i class="fas fa-redo"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div id="reports-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Payment Reports</h2>
                            <div style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" style="width: auto;" id="reportStartDate">
                                <input type="date" class="form-control" style="width: auto;" id="reportEndDate">
                                <button class="btn btn-primary btn-sm" onclick="generateReport()">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button class="btn btn-success btn-sm" onclick="downloadReport()">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div
                            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                            <div class="card"
                                style="text-align: center; padding: 20px; background: linear-gradient(135deg, #28a745, #20c997);">
                                <h4 style="color: white; opacity: 0.9; margin-bottom: 10px;">Total Revenue</h4>
                                <h2 style="color: white;">৳8,400</h2>
                            </div>
                            <div class="card"
                                style="text-align: center; padding: 20px; background: linear-gradient(135deg, #E2136E, #ff6b9d);">
                                <h4 style="color: white; opacity: 0.9; margin-bottom: 10px;">bKash</h4>
                                <h2 style="color: white;">৳4,200</h2>
                            </div>
                            <div class="card"
                                style="text-align: center; padding: 20px; background: linear-gradient(135deg, #F6A21E, #ffc107);">
                                <h4 style="color: white; opacity: 0.9; margin-bottom: 10px;">Nagad</h4>
                                <h2 style="color: white;">৳2,800</h2>
                            </div>
                            <div class="card"
                                style="text-align: center; padding: 20px; background: linear-gradient(135deg, #8C3494, #a855f7);">
                                <h4 style="color: white; opacity: 0.9; margin-bottom: 10px;">Rocket</h4>
                                <h2 style="color: white;">৳1,400</h2>
                            </div>
                        </div>

                        <!-- Report Table -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Payment Report - December 2025</h4>
                                <span style="color: var(--gray-500);">42 transactions</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="reportTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction ID</th>
                                            <th>Employer</th>
                                            <th>Job Title</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>29 Dec 2025</td>
                                            <td><code>TXN202512290001</code></td>
                                            <td>Tech Solutions Inc</td>
                                            <td>Senior PHP Developer</td>
                                            <td>bKash</td>
                                            <td>৳200</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td>28 Dec 2025</td>
                                            <td><code>TXN202512280002</code></td>
                                            <td>Digital Agency BD</td>
                                            <td>UI/UX Designer</td>
                                            <td>Nagad</td>
                                            <td>৳200</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td>28 Dec 2025</td>
                                            <td><code>TXN202512280003</code></td>
                                            <td>Software House</td>
                                            <td>Full Stack Developer</td>
                                            <td>Rocket</td>
                                            <td>৳200</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>27 Dec 2025</td>
                                            <td><code>TXN202512270004</code></td>
                                            <td>StartUp BD</td>
                                            <td>Marketing Manager</td>
                                            <td>bKash</td>
                                            <td>৳200</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td>26 Dec 2025</td>
                                            <td><code>TXN202512260005</code></td>
                                            <td>BD Tech Corp</td>
                                            <td>DevOps Engineer</td>
                                            <td>Nagad</td>
                                            <td>৳200</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Report Summary -->
                            <div
                                style="padding: 20px; background: var(--gray-100); border-top: 1px solid var(--gray-200);">
                                <div
                                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center;">
                                    <div>
                                        <strong>Total Transactions</strong>
                                        <p style="margin: 5px 0 0; font-size: 24px; color: var(--primary-color);">42</p>
                                    </div>
                                    <div>
                                        <strong>Completed</strong>
                                        <p style="margin: 5px 0 0; font-size: 24px; color: var(--success-color);">39</p>
                                    </div>
                                    <div>
                                        <strong>Pending</strong>
                                        <p style="margin: 5px 0 0; font-size: 24px; color: var(--warning-color);">3</p>
                                    </div>
                                    <div>
                                        <strong>Total Revenue</strong>
                                        <p style="margin: 5px 0 0; font-size: 24px; color: var(--success-color);">৳8,400
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Tab -->
                    <div id="users-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>User Management</h2>
                            <select class="form-control" style="width: auto;">
                                <option value="">All Users</option>
                                <option value="candidate">Candidates</option>
                                <option value="employer">Employers</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Smith</td>
                                        <td>employer@test.com</td>
                                        <td><span class="badge badge-info">Employer</span></td>
                                        <td>Dec 1, 2025</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-warning"><i class="fas fa-ban"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jane Doe</td>
                                        <td>candidate@test.com</td>
                                        <td><span class="badge badge-primary">Candidate</span></td>
                                        <td>Dec 5, 2025</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-warning"><i class="fas fa-ban"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Jobs Tab -->
                    <div id="jobs-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Job Management</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Payment</th>
                                        <th>Posted</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Senior PHP Developer</td>
                                        <td>Tech Solutions Inc</td>
                                        <td><span class="badge badge-success">Paid ৳200</span></td>
                                        <td>Dec 29, 2025</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Frontend Developer</td>
                                        <td>Tech Solutions Inc</td>
                                        <td><span class="badge badge-success">Paid ৳200</span></td>
                                        <td>Dec 28, 2025</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Companies Tab -->
                    <div id="companies-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Company Management</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Owner</th>
                                        <th>Jobs Posted</th>
                                        <th>Total Paid</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tech Solutions Inc</td>
                                        <td>John Smith</td>
                                        <td>8</td>
                                        <td>৳1,600</td>
                                        <td><span class="badge badge-success">Verified</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Digital Agency BD</td>
                                        <td>Rahman Ali</td>
                                        <td>5</td>
                                        <td>৳1,000</td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div id="settings-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Site Settings</h2>
                        </div>

                        <div class="card">
                            <h4><i class="fas fa-money-bill-wave"></i> Payment Settings</h4>
                            <form style="margin-top: 20px;" id="paymentSettingsForm">
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                    <div class="form-group">
                                        <label class="form-label">Job Posting Fee (BDT)</label>
                                        <input type="number" class="form-control" value="200" id="jobPostingFee">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Currency</label>
                                        <select class="form-control">
                                            <option value="BDT" selected>BDT (৳)</option>
                                        </select>
                                    </div>
                                </div>

                                <h5
                                    style="margin: 30px 0 15px; padding-top: 20px; border-top: 1px solid var(--gray-200);">
                                    <i class="fas fa-mobile-alt"></i> Mobile Banking Numbers
                                </h5>

                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                                    <div class="form-group">
                                        <label class="form-label" style="color: #E2136E;">
                                            <i class="fas fa-wallet"></i> bKash Number
                                        </label>
                                        <input type="text" class="form-control" value="01712345678" id="bkashNumber">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="color: #F6A21E;">
                                            <i class="fas fa-wallet"></i> Nagad Number
                                        </label>
                                        <input type="text" class="form-control" value="01812345678" id="nagadNumber">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="color: #8C3494;">
                                            <i class="fas fa-wallet"></i> Rocket Number
                                        </label>
                                        <input type="text" class="form-control" value="01912345678" id="rocketNumber">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- Payment Details Modal -->
    <div id="paymentModal" class="modal hidden">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Payment Details</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="paymentModalBody">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.classList.add('hidden');
            });
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        function viewPayment(txnId) {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModalBody').innerHTML = `
                <div style="padding: 20px;">
                    <p><strong>Transaction ID:</strong> ${txnId}</p>
                    <p><strong>Amount:</strong> ৳200</p>
                    <p><strong>Status:</strong> <span class="badge badge-success">Completed</span></p>
                    <p><strong>Payment Method:</strong> bKash</p>
                    <p><strong>Payer Phone:</strong> 01712345678</p>
                    <p><strong>Date:</strong> Dec 29, 2025</p>
                </div>
            `;
        }

        function closeModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        function confirmPayment(txnId) {
            if (confirm('Confirm this payment as completed?')) {
                alert('Payment ' + txnId + ' has been confirmed!');
                location.reload();
            }
        }

        function rejectPayment(txnId) {
            if (confirm('Reject this payment?')) {
                alert('Payment ' + txnId + ' has been rejected.');
                location.reload();
            }
        }

        function generateReport() {
            alert('Report generated! (Demo)');
        }

        function downloadReport() {
            alert('Downloading payment report as CSV... (Demo)');
        }

        document.getElementById('paymentSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Settings saved successfully!');
        });
    </script>
</body>

</html>