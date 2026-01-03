<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';

// Check if user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - JobPortal</title>
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
                    <li><a href="jobs.php?user=employer" class="nav-link">Browse Jobs</a></li>
                </ul>

                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="nav-actions">
                    <a href="post-job.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Post Job
                    </a>
                    <span style="color: var(--gray-600); margin-left: 15px;">Welcome, <?php echo htmlspecialchars($userName); ?></span>
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
                            style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 style="margin-bottom: 5px;">Tech Solutions Inc</h4>
                        <p style="color: var(--gray-500); font-size: 14px; margin: 0;">Employer</p>
                    </div>

                    <nav class="sidebar-nav">
                        <a href="#" class="active" onclick="showTab('overview')">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a href="#" onclick="showTab('company')">
                            <i class="fas fa-building"></i> Company Profile
                        </a>
                        <a href="#" onclick="showTab('jobs')">
                            <i class="fas fa-briefcase"></i> Manage Jobs
                        </a>
                        <a href="#" onclick="showTab('applications')">
                            <i class="fas fa-users"></i> Applications
                            <span class="badge badge-danger" style="margin-left: auto;">12</span>
                        </a>
                        <a href="#" onclick="showTab('interviews')">
                            <i class="fas fa-calendar-alt"></i> Interviews
                        </a>
                        <a href="post-job.html">
                            <i class="fas fa-plus-circle"></i> Post New Job
                        </a>
                        <a href="#" onclick="showTab('payments')">
                            <i class="fas fa-money-bill-wave"></i> Payment History
                        </a>
                        <a href="#" onclick="showTab('messages')">
                            <i class="fas fa-envelope"></i> Messages
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
                            <h2>Dashboard Overview</h2>
                            <a href="post-job.html" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Post New Job
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <i class="fas fa-briefcase"></i>
                                <h3>8</h3>
                                <p>Active Jobs</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-users"></i>
                                <h3>124</h3>
                                <p>Total Applications</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-money-bill-wave" style="color: var(--success-color);"></i>
                                <h3>৳1,600</h3>
                                <p>Total Paid</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-eye"></i>
                                <h3>2.4k</h3>
                                <p>Job Views</p>
                            </div>
                        </div>

                        <!-- Recent Applications -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent Applications</h4>
                                <a href="#" onclick="showTab('applications')">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Candidate</th>
                                            <th>Job</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div
                                                        style="width: 35px; height: 35px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    Jane Doe
                                                </div>
                                            </td>
                                            <td>Senior PHP Developer</td>
                                            <td>Dec 27, 2025</td>
                                            <td><span class="badge badge-warning">New</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div
                                                        style="width: 35px; height: 35px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    Mike Johnson
                                                </div>
                                            </td>
                                            <td>Frontend Developer</td>
                                            <td>Dec 26, 2025</td>
                                            <td><span class="badge badge-info">Reviewed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div
                                                        style="width: 35px; height: 35px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    Sarah Wilson
                                                </div>
                                            </td>
                                            <td>UI/UX Designer</td>
                                            <td>Dec 25, 2025</td>
                                            <td><span class="badge badge-success">Shortlisted</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary">View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Active Jobs -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Your Active Jobs</h4>
                                <a href="#" onclick="showTab('jobs')">Manage All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Applications</th>
                                            <th>Views</th>
                                            <th>Expires</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Senior PHP Developer</td>
                                            <td>45</td>
                                            <td>892</td>
                                            <td>Jan 27, 2026</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>Frontend Developer</td>
                                            <td>38</td>
                                            <td>654</td>
                                            <td>Jan 25, 2026</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>UI/UX Designer</td>
                                            <td>28</td>
                                            <td>521</td>
                                            <td>Jan 20, 2026</td>
                                            <td><span class="badge badge-success">Active</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Company Profile Tab -->
                    <div id="company-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Company Profile</h2>
                        </div>

                        <form id="companyForm">
                            <div class="form-group">
                                <label class="form-label">Company Logo</label>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div
                                        style="width: 100px; height: 100px; border-radius: var(--border-radius); background: var(--gray-200); display: flex; align-items: center; justify-content: center; font-size: 40px;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <input type="file" class="form-control" style="max-width: 300px;">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" value="Tech Solutions Inc">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Industry</label>
                                    <select class="form-control">
                                        <option value="technology" selected>Technology</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="finance">Finance</option>
                                        <option value="education">Education</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Company Size</label>
                                    <select class="form-control">
                                        <option value="1-10">1-10 employees</option>
                                        <option value="11-50">11-50 employees</option>
                                        <option value="50-200" selected>50-200 employees</option>
                                        <option value="200-500">200-500 employees</option>
                                        <option value="500+">500+ employees</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Founded Year</label>
                                    <input type="number" class="form-control" value="2015">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-control" value="https://techsolutions.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" class="form-control" value="New York, USA">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="hr@techsolutions.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" value="+1 234 567 890">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">About Company</label>
                                <textarea class="form-control"
                                    rows="5">Tech Solutions Inc is a leading technology company providing innovative solutions for businesses worldwide. We specialize in web development, mobile applications, and cloud services.</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Manage Jobs Tab -->
                    <div id="jobs-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Manage Jobs</h2>
                            <a href="post-job.html" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Post New Job
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Type</th>
                                        <th>Applications</th>
                                        <th>Expires</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Senior PHP Developer</strong></td>
                                        <td>Full-time</td>
                                        <td>45</td>
                                        <td>Jan 27, 2026</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <a href="job-single.php" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-eye"></i></a>
                                            <button class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Frontend Developer</strong></td>
                                        <td>Full-time</td>
                                        <td>38</td>
                                        <td>Jan 25, 2026</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                            <button class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>UI/UX Designer</strong></td>
                                        <td>Full-time</td>
                                        <td>28</td>
                                        <td>Jan 20, 2026</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                            <button class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Digital Marketing Manager</strong></td>
                                        <td>Full-time</td>
                                        <td>13</td>
                                        <td>Dec 15, 2025</td>
                                        <td><span class="badge badge-secondary">Closed</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                            <button class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-redo"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Applications Tab -->
                    <div id="applications-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Applications</h2>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <button class="btn btn-primary btn-sm" onclick="openAIShortlistModal()">
                                    <i class="fas fa-robot"></i> AI Shortlisting
                                </button>
                                <select class="form-control" style="width: auto;" id="jobFilter">
                                    <option value="">All Jobs</option>
                                    <option value="1">Senior PHP Developer</option>
                                    <option value="2">Frontend Developer</option>
                                    <option value="3">UI/UX Designer</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="applicationsTable">
                                <thead>
                                    <tr>
                                        <th>Candidate</th>
                                        <th>Job</th>
                                        <th>Applied</th>
                                        <th>AI Score</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicationsBody">
                                    <tr id="app-1">
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>Jane Doe</strong>
                                                    <div style="font-size: 12px; color: var(--gray-500);">PHP,
                                                        JavaScript, MySQL</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Senior PHP Developer</td>
                                        <td>Dec 27, 2025</td>
                                        <td><span class="badge"
                                                style="background: linear-gradient(135deg, #10b981, #059669); color: white;">92%</span>
                                        </td>
                                        <td><span class="badge badge-warning" id="status-1">New</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="viewCandidate(1, 'Jane Doe', 'jane.doe@email.com', '+880 1712345678', 'PHP, JavaScript, MySQL, Laravel, React', '5 years', 'Senior PHP Developer')">View</button>
                                            <button class="btn btn-sm btn-success" id="shortlist-btn-1"
                                                onclick="shortlistCandidate(1)">Shortlist</button>
                                            <button class="btn btn-sm btn-danger" id="reject-btn-1"
                                                onclick="rejectCandidate(1)">Reject</button>
                                        </td>
                                    </tr>
                                    <tr id="app-2">
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>Mike Johnson</strong>
                                                    <div style="font-size: 12px; color: var(--gray-500);">React, Vue.js,
                                                        TypeScript</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Frontend Developer</td>
                                        <td>Dec 26, 2025</td>
                                        <td><span class="badge"
                                                style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">78%</span>
                                        </td>
                                        <td><span class="badge badge-info" id="status-2">Reviewed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="viewCandidate(2, 'Mike Johnson', 'mike.j@email.com', '+880 1812345678', 'React, Vue.js, TypeScript, Node.js', '3 years', 'Frontend Developer')">View</button>
                                            <button class="btn btn-sm btn-success" id="shortlist-btn-2"
                                                onclick="shortlistCandidate(2)">Shortlist</button>
                                            <button class="btn btn-sm btn-danger" id="reject-btn-2"
                                                onclick="rejectCandidate(2)">Reject</button>
                                        </td>
                                    </tr>
                                    <tr id="app-3">
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>Sarah Wilson</strong>
                                                    <div style="font-size: 12px; color: var(--gray-500);">Figma, Adobe
                                                        XD, Sketch</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>UI/UX Designer</td>
                                        <td>Dec 25, 2025</td>
                                        <td><span class="badge"
                                                style="background: linear-gradient(135deg, #10b981, #059669); color: white;">88%</span>
                                        </td>
                                        <td><span class="badge badge-success" id="status-3">Shortlisted</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="viewCandidate(3, 'Sarah Wilson', 'sarah.w@email.com', '+880 1912345678', 'Figma, Adobe XD, Sketch, UI/UX', '4 years', 'UI/UX Designer')">View</button>
                                            <button class="btn btn-sm btn-primary"
                                                onclick="messageCandidate(3)">Message</button>
                                            <button class="btn btn-sm btn-danger" id="reject-btn-3"
                                                onclick="rejectCandidate(3)">Reject</button>
                                        </td>
                                    </tr>
                                    <tr id="app-4">
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>Ahmed Rahman</strong>
                                                    <div style="font-size: 12px; color: var(--gray-500);">Python,
                                                        Django, PostgreSQL</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Senior PHP Developer</td>
                                        <td>Dec 24, 2025</td>
                                        <td><span class="badge"
                                                style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">45%</span>
                                        </td>
                                        <td><span class="badge badge-warning" id="status-4">New</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="viewCandidate(4, 'Ahmed Rahman', 'ahmed.r@email.com', '+880 1612345678', 'Python, Django, PostgreSQL', '2 years', 'Senior PHP Developer')">View</button>
                                            <button class="btn btn-sm btn-success" id="shortlist-btn-4"
                                                onclick="shortlistCandidate(4)">Shortlist</button>
                                            <button class="btn btn-sm btn-danger" id="reject-btn-4"
                                                onclick="rejectCandidate(4)">Reject</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Interviews Tab -->
                    <div id="interviews-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Interviews</h2>
                            <button class="btn btn-primary btn-sm" onclick="openScheduleInterviewModal()">
                                <i class="fas fa-plus"></i> Schedule Interview
                            </button>
                        </div>

                        <div class="interviews-container" id="employerInterviewsContainer">
                            <!-- Interviews will be loaded here -->
                        </div>
                    </div>

                    <!-- Messages Tab -->
                    <div id="messages-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Messages</h2>
                        </div>
                        <div class="alert alert-info">No new messages</div>
                    </div>

                    <!-- Payment History Tab -->
                    <div id="payments-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Payment History</h2>
                            <a href="post-job.html" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Post New Job (৳200)
                            </a>
                        </div>

                        <!-- Payment Summary -->
                        <div
                            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                            <div class="stat-card" style="border-left: 4px solid var(--success-color);">
                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                <h3>৳1,600</h3>
                                <p>Total Paid</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--primary-color);">
                                <i class="fas fa-briefcase" style="color: var(--primary-color);"></i>
                                <h3>8</h3>
                                <p>Jobs Posted</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--warning-color);">
                                <i class="fas fa-clock" style="color: var(--warning-color);"></i>
                                <h3>1</h3>
                                <p>Pending</p>
                            </div>
                            <div class="stat-card" style="border-left: 4px solid var(--info-color);">
                                <i class="fas fa-receipt" style="color: var(--info-color);"></i>
                                <h3>9</h3>
                                <p>Total Transactions</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="card"
                            style="margin-bottom: 20px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-left: 4px solid var(--success-color);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fas fa-info-circle" style="font-size: 24px; color: var(--success-color);"></i>
                                <div>
                                    <strong>Job Posting Fee: ৳200 BDT</strong>
                                    <p style="margin: 5px 0 0; color: var(--gray-600);">
                                        Each job posting requires a fee of ৳200. Payment is verified within 24 hours.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="card" style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;"><i class="fas fa-wallet"></i> Accepted Payment Methods</h4>
                            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 40px; height: 40px; background: #E2136E; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: white; font-weight: bold; font-size: 10px;">bKash</span>
                                    </div>
                                    <div>
                                        <strong>bKash</strong>
                                        <div style="font-size: 12px; color: var(--gray-500);">01712345678</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 40px; height: 40px; background: #F6A21E; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: white; font-weight: bold; font-size: 10px;">Nagad</span>
                                    </div>
                                    <div>
                                        <strong>Nagad</strong>
                                        <div style="font-size: 12px; color: var(--gray-500);">01812345678</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 40px; height: 40px; background: #8C3494; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: white; font-weight: bold; font-size: 10px;">Rocket</span>
                                    </div>
                                    <div>
                                        <strong>Rocket</strong>
                                        <div style="font-size: 12px; color: var(--gray-500);">01912345678</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment History Table -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Transaction History</h4>
                                <select class="form-control" style="width: auto;" id="paymentFilter">
                                    <option value="">All Transactions</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Job Title</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>TXN202512290001</code></td>
                                            <td>Senior PHP Developer</td>
                                            <td><span class="badge"
                                                    style="background: #E2136E; color: white;">bKash</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 29, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512290001')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512280002</code></td>
                                            <td>Frontend Developer</td>
                                            <td><span class="badge"
                                                    style="background: #F6A21E; color: white;">Nagad</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 28, 2025</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>
                                                <span
                                                    style="color: var(--gray-500); font-size: 12px;">Verifying...</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512250003</code></td>
                                            <td>UI/UX Designer</td>
                                            <td><span class="badge"
                                                    style="background: #E2136E; color: white;">bKash</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 25, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512250003')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512200004</code></td>
                                            <td>Digital Marketing Manager</td>
                                            <td><span class="badge"
                                                    style="background: #8C3494; color: white;">Rocket</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 20, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512200004')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512150005</code></td>
                                            <td>Data Analyst</td>
                                            <td><span class="badge"
                                                    style="background: #F6A21E; color: white;">Nagad</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 15, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512150005')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512100006</code></td>
                                            <td>DevOps Engineer</td>
                                            <td><span class="badge"
                                                    style="background: #E2136E; color: white;">bKash</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 10, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512100006')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512050007</code></td>
                                            <td>Backend Developer</td>
                                            <td><span class="badge"
                                                    style="background: #E2136E; color: white;">bKash</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 5, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512050007')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>TXN202512010008</code></td>
                                            <td>Project Manager</td>
                                            <td><span class="badge"
                                                    style="background: #8C3494; color: white;">Rocket</span></td>
                                            <td><strong>৳200</strong></td>
                                            <td>Dec 1, 2025</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="viewReceipt('TXN202512010008')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div id="settings-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Settings</h2>
                        </div>

                        <div class="card">
                            <h4>Account Settings</h4>
                            <form style="margin-top: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="employer@test.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Account</button>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

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

            // Load interviews if interviews tab is selected
            if (tabName === 'interviews') {
                loadEmployerInterviews();
            }
        }

        // View Candidate Details
        function viewCandidate(id, name, email, phone, skills, experience, appliedFor) {
            const candidateHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 style="margin-bottom: 5px;">${name}</h3>
                    <p style="color: var(--gray-500);">Applied for: ${appliedFor}</p>
                </div>
                
                <div style="background: var(--gray-100); padding: 20px; border-radius: var(--border-radius); margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-envelope" style="color: var(--primary-color); width: 20px;"></i>
                        <div>
                            <small style="color: var(--gray-500);">Email</small>
                            <div><strong>${email}</strong></div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-phone" style="color: var(--primary-color); width: 20px;"></i>
                        <div>
                            <small style="color: var(--gray-500);">Phone</small>
                            <div><strong>${phone}</strong></div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-briefcase" style="color: var(--primary-color); width: 20px;"></i>
                        <div>
                            <small style="color: var(--gray-500);">Experience</small>
                            <div><strong>${experience}</strong></div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-code" style="color: var(--primary-color); width: 20px; margin-top: 3px;"></i>
                        <div>
                            <small style="color: var(--gray-500);">Skills</small>
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 5px;">
                                ${skills.split(', ').map(skill => `<span class="badge" style="background: var(--gray-200); color: var(--gray-700);">${skill}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h5 style="margin-bottom: 10px;"><i class="fas fa-file-pdf"></i> Resume</h5>
                    <a href="#" class="btn btn-outline btn-sm" style="width: 100%;">
                        <i class="fas fa-download"></i> Download Resume (PDF)
                    </a>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-success" style="flex: 1;" onclick="shortlistCandidate(${id}); closeCandidateModal();">
                        <i class="fas fa-check"></i> Shortlist
                    </button>
                    <button class="btn btn-danger" style="flex: 1;" onclick="rejectCandidate(${id}); closeCandidateModal();">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            `;

            openModal('candidateModal', 'Candidate Details', candidateHTML);
        }

        // Shortlist Candidate
        function shortlistCandidate(id) {
            const statusBadge = document.getElementById('status-' + id);
            const shortlistBtn = document.getElementById('shortlist-btn-' + id);

            if (statusBadge) {
                statusBadge.className = 'badge badge-success';
                statusBadge.textContent = 'Shortlisted';
            }

            if (shortlistBtn) {
                shortlistBtn.outerHTML = `<button class="btn btn-sm btn-primary" onclick="messageCandidate(${id})">Message</button>`;
            }

            showNotification('Candidate shortlisted successfully!', 'success');
        }

        // Reject Candidate
        function rejectCandidate(id) {
            const statusBadge = document.getElementById('status-' + id);
            const row = document.getElementById('app-' + id);

            if (statusBadge) {
                statusBadge.className = 'badge badge-danger';
                statusBadge.textContent = 'Rejected';
            }

            // Grey out the row
            if (row) {
                row.style.opacity = '0.5';
                row.style.background = 'var(--gray-100)';
            }

            // Replace buttons
            const actionCell = row.querySelector('td:last-child');
            if (actionCell) {
                actionCell.innerHTML = `
                    <button class="btn btn-sm btn-secondary" disabled>Rejected</button>
                    <button class="btn btn-sm btn-outline" onclick="undoReject(${id})">Undo</button>
                `;
            }

            showNotification('Candidate rejected.', 'warning');
        }

        // Undo Reject
        function undoReject(id) {
            const statusBadge = document.getElementById('status-' + id);
            const row = document.getElementById('app-' + id);

            if (statusBadge) {
                statusBadge.className = 'badge badge-warning';
                statusBadge.textContent = 'New';
            }

            if (row) {
                row.style.opacity = '1';
                row.style.background = '';
            }

            // Restore buttons
            const actionCell = row.querySelector('td:last-child');
            if (actionCell) {
                actionCell.innerHTML = `
                    <button class="btn btn-sm btn-secondary" onclick="viewCandidate(${id}, 'Candidate', 'email@test.com', '+880 1700000000', 'Skills', '3 years', 'Position')">View</button>
                    <button class="btn btn-sm btn-success" id="shortlist-btn-${id}" onclick="shortlistCandidate(${id})">Shortlist</button>
                    <button class="btn btn-sm btn-danger" id="reject-btn-${id}" onclick="rejectCandidate(${id})">Reject</button>
                `;
            }

            showNotification('Action undone.', 'info');
        }

        // Message Candidate
        function messageCandidate(id) {
            showNotification('Opening message composer... (Demo)', 'info');
        }

        // AI Shortlisting Modal
        function openAIShortlistModal() {
            const aiHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 style="margin-bottom: 5px;">AI Shortlisting</h3>
                    <p style="color: var(--gray-500);">Let AI analyze and rank candidates</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Select Job Position</label>
                    <select class="form-control" id="aiJobSelect">
                        <option value="1">Senior PHP Developer</option>
                        <option value="2">Frontend Developer</option>
                        <option value="3">UI/UX Designer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Minimum Match Score</label>
                    <input type="range" id="aiScoreRange" min="50" max="100" value="70" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--gray-500);">
                        <span>50%</span>
                        <span id="aiScoreValue">70%</span>
                        <span>100%</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Priority Skills (comma separated)</label>
                    <input type="text" class="form-control" id="aiSkills" placeholder="e.g., PHP, Laravel, MySQL">
                </div>
                
                <div class="alert alert-info" style="margin: 20px 0;">
                    <i class="fas fa-info-circle"></i> AI will analyze resumes, skills, and experience to rank candidates based on job requirements.
                </div>
                
                <button class="btn btn-primary" style="width: 100%;" onclick="runAIShortlisting()">
                    <i class="fas fa-magic"></i> Run AI Analysis
                </button>
            `;

            openModal('aiModal', '<i class="fas fa-robot"></i> AI Shortlisting', aiHTML);

            // Add range slider listener
            setTimeout(() => {
                const rangeSlider = document.getElementById('aiScoreRange');
                const scoreValue = document.getElementById('aiScoreValue');
                if (rangeSlider && scoreValue) {
                    rangeSlider.addEventListener('input', function() {
                        scoreValue.textContent = this.value + '%';
                    });
                }
            }, 100);
        }

        // Run AI Shortlisting
        function runAIShortlisting() {
            const modal = document.getElementById('aiModal');
            const modalBody = modal.querySelector('.modal-body');

            // Show loading
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div class="spinner" style="width: 60px; height: 60px; border: 4px solid var(--gray-200); border-top: 4px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                    <h4>Analyzing Candidates...</h4>
                    <p style="color: var(--gray-500);">AI is reviewing resumes and matching skills</p>
                </div>
            `;

            // Simulate AI processing
            setTimeout(() => {
                modalBody.innerHTML = `
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--success-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 15px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3>Analysis Complete!</h3>
                        <p style="color: var(--gray-500);">4 candidates analyzed</p>
                    </div>
                    
                    <div style="background: var(--gray-100); padding: 15px; border-radius: var(--border-radius); margin-bottom: 15px;">
                        <h5 style="margin-bottom: 15px;"><i class="fas fa-trophy" style="color: gold;"></i> Top Candidates</h5>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: white; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid var(--success-color);">
                            <div>
                                <strong>Jane Doe</strong>
                                <div style="font-size: 12px; color: var(--gray-500);">PHP, JavaScript, MySQL</div>
                            </div>
                            <span class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 14px;">92%</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: white; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid var(--success-color);">
                            <div>
                                <strong>Sarah Wilson</strong>
                                <div style="font-size: 12px; color: var(--gray-500);">Figma, Adobe XD</div>
                            </div>
                            <span class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 14px;">88%</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: white; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid var(--warning-color);">
                            <div>
                                <strong>Mike Johnson</strong>
                                <div style="font-size: 12px; color: var(--gray-500);">React, Vue.js</div>
                            </div>
                            <span class="badge" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-size: 14px;">78%</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: white; border-radius: 8px; border-left: 4px solid var(--danger-color);">
                            <div>
                                <strong>Ahmed Rahman</strong>
                                <div style="font-size: 12px; color: var(--gray-500);">Python, Django</div>
                            </div>
                            <span class="badge" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 14px;">45%</span>
                        </div>
                    </div>
                    
                    <div class="alert" style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46;">
                        <i class="fas fa-lightbulb"></i> <strong>AI Recommendation:</strong> Jane Doe has the highest match score with 92% compatibility for the Senior PHP Developer position.
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button class="btn btn-success" style="flex: 1;" onclick="autoShortlistTop(); closeModal('aiModal');">
                            <i class="fas fa-check-double"></i> Shortlist Top 2
                        </button>
                        <button class="btn btn-secondary" style="flex: 1;" onclick="closeModal('aiModal');">
                            Close
                        </button>
                    </div>
                `;
            }, 2000);
        }

        // Auto Shortlist Top Candidates
        function autoShortlistTop() {
            shortlistCandidate(1);
            shortlistCandidate(3);
            showNotification('Top 2 candidates have been shortlisted!', 'success');
        }

        // Generic Modal Functions
        function openModal(modalId, title, content) {
            // Create modal if not exists
            if (!document.getElementById(modalId)) {
                const modal = document.createElement('div');
                modal.id = modalId;
                modal.className = 'modal';
                modal.innerHTML = `
                    <div class="modal-overlay" onclick="closeModal('${modalId}')"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="${modalId}-title">${title}</h4>
                            <button class="modal-close" onclick="closeModal('${modalId}')">&times;</button>
                        </div>
                        <div class="modal-body" id="${modalId}-body">${content}</div>
                    </div>
                `;
                document.body.appendChild(modal);
            } else {
                document.getElementById(modalId + '-title').innerHTML = title;
                document.getElementById(modalId + '-body').innerHTML = content;
            }

            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function closeCandidateModal() {
            closeModal('candidateModal');
        }

        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = 'notification notification-' + type;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'}"></i>
                ${message}
            `;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 3000;
                display: flex;
                align-items: center;
                gap: 10px;
                animation: slideIn 0.3s ease;
                background: ${type === 'success' ? '#10b981' : type === 'warning' ? '#f59e0b' : type === 'danger' ? '#ef4444' : '#3b82f6'};
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function viewReceipt(txnId) {
            const receiptHTML = `
                <div style="text-align: center; padding: 20px;">
                    <div style="background: var(--success-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-check" style="font-size: 30px;"></i>
                    </div>
                    <h3 style="margin-bottom: 5px;">Payment Successful</h3>
                    <p style="color: var(--gray-500);">Job Posting Fee</p>
                    
                    <div style="background: var(--gray-100); padding: 20px; border-radius: var(--border-radius); margin: 20px 0; text-align: left;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--gray-500);">Transaction ID</span>
                            <strong>${txnId}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--gray-500);">Amount</span>
                            <strong style="color: var(--success-color);">৳200 BDT</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--gray-500);">Payment Method</span>
                            <strong>bKash</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--gray-500);">Status</span>
                            <span class="badge badge-success">Completed</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--gray-500);">Date</span>
                            <strong>Dec 29, 2025</strong>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px dashed var(--gray-300); padding-top: 20px; margin-top: 20px;">
                        <p style="color: var(--gray-500); font-size: 12px; margin-bottom: 15px;">
                            <i class="fas fa-building"></i> Tech Solutions Inc<br>
                            JobPortal - Bangladesh
                        </p>
                        <button class="btn btn-secondary btn-sm" onclick="printReceipt()">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                    </div>
                </div>
            `;

            // Create modal if not exists
            if (!document.getElementById('receiptModal')) {
                const modal = document.createElement('div');
                modal.id = 'receiptModal';
                modal.className = 'modal';
                modal.innerHTML = `
                    <div class="modal-overlay" onclick="closeReceiptModal()"></div>
                    <div class="modal-content" style="max-width: 400px;">
                        <div class="modal-header">
                            <h4><i class="fas fa-receipt"></i> Payment Receipt</h4>
                            <button class="modal-close" onclick="closeReceiptModal()">&times;</button>
                        </div>
                        <div class="modal-body" id="receiptContent"></div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            document.getElementById('receiptContent').innerHTML = receiptHTML;
            document.getElementById('receiptModal').classList.add('active');
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.remove('active');
        }

        function printReceipt() {
            window.print();
        }

        // Interview Management Functions
        async function loadEmployerInterviews() {
            try {
                const response = await fetch('php/api/interview-api.php?action=list');
                const data = await response.json();

                if (data.success) {
                    displayEmployerInterviews(data.interviews);
                } else {
                    document.getElementById('employerInterviewsContainer').innerHTML = '<p class="text-center text-muted">Failed to load interviews</p>';
                }
            } catch (error) {
                console.error('Error loading interviews:', error);
                document.getElementById('employerInterviewsContainer').innerHTML = '<p class="text-center text-muted">Error loading interviews</p>';
            }
        }

        // Auto-refresh interviews every 30 seconds
        setInterval(() => {
            if (document.getElementById('interviews-tab') && !document.getElementById('interviews-tab').classList.contains('hidden')) {
                loadEmployerInterviews();
            }
        }, 30000);

        function displayEmployerInterviews(interviews) {
            const container = document.getElementById('employerInterviewsContainer');

            if (interviews.length === 0) {
                container.innerHTML = '<div class="text-center" style="padding: 40px;"><i class="fas fa-calendar-alt fa-3x text-muted"></i><h4 style="margin-top: 20px; color: var(--gray-500);">No interviews scheduled</h4><p style="color: var(--gray-400);">Schedule interviews for shortlisted candidates.</p></div>';
                return;
            }

            const html = interviews.map(interview => `
                <div class="interview-card" style="background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 24px; margin-bottom: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="interview-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div>
                            <h4 style="margin: 0 0 8px 0; color: var(--primary-color);">${interview.job_title}</h4>
                            <p style="margin: 0; color: var(--gray-600); font-size: 16px;">with ${interview.candidate_name}</p>
                        </div>
                        <span class="badge badge-${getStatusColor(interview.status)}">${interview.status}</span>
                    </div>

                    <div class="interview-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                        <div>
                            <i class="fas fa-calendar"></i>
                            <strong>Date:</strong> ${new Date(interview.interview_date).toLocaleDateString()}
                        </div>
                        <div>
                            <i class="fas fa-clock"></i>
                            <strong>Time:</strong> ${new Date(interview.interview_date).toLocaleTimeString()}
                        </div>
                        <div>
                            <i class="fas fa-video"></i>
                            <strong>Type:</strong> ${interview.interview_type}
                        </div>
                        ${interview.platform ? `<div><i class="fas fa-globe"></i><strong>Platform:</strong> ${interview.platform}</div>` : ''}
                    </div>

                    ${interview.notes ? `<div class="interview-notes" style="background: var(--gray-50); padding: 12px; border-radius: 8px; margin-bottom: 16px;"><strong>Notes:</strong> ${interview.notes}</div>` : ''}

                    <div class="interview-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
                        ${interview.meeting_link ? `<a href="${interview.meeting_link}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Join Meeting</a>` : ''}
                        <button class="btn btn-outline btn-sm" onclick="editInterview(${interview.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="cancelInterview(${interview.id})">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            `).join('');

            container.innerHTML = html;
        }

        function openScheduleInterviewModal() {
            const modalHTML = `
                <div class="modal-overlay" id="scheduleInterviewModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 2000;">
                    <div class="modal-content" style="background: white; border-radius: 12px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
                        <div class="modal-header" style="padding: 20px; border-bottom: 1px solid var(--gray-200);">
                            <h4 style="margin: 0;">Schedule Interview</h4>
                            <button class="modal-close" onclick="closeScheduleInterviewModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
                        </div>
                        <div class="modal-body" style="padding: 20px;">
                            <form id="scheduleInterviewForm">
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Select Application</label>
                                    <select class="form-control" name="application_id" required>
                                        <option value="">Choose an application...</option>
                                        <!-- Applications will be loaded here -->
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Interview Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="interview_date" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Interview Type</label>
                                    <select class="form-control" name="interview_type">
                                        <option value="video">Video Call</option>
                                        <option value="phone">Phone Call</option>
                                        <option value="in_person">In Person</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Platform (Zoom, Meet, etc.)</label>
                                    <input type="text" class="form-control" name="platform" placeholder="e.g., Zoom, Google Meet">
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Meeting Link</label>
                                    <input type="url" class="form-control" name="meeting_link" placeholder="https://zoom.us/...">
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes for the candidate..."></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer" style="padding: 20px; border-top: 1px solid var(--gray-200); display: flex; gap: 12px; justify-content: flex-end;">
                            <button class="btn btn-secondary" onclick="closeScheduleInterviewModal()">Cancel</button>
                            <button class="btn btn-primary" onclick="scheduleInterview()">Schedule Interview</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            loadApplicationsForInterview();
        }

        function closeScheduleInterviewModal() {
            const modal = document.getElementById('scheduleInterviewModal');
            if (modal) modal.remove();
        }

        async function loadApplicationsForInterview() {
            try {
                const response = await fetch('php/api/applications-api.php?action=list&status=shortlisted');
                const data = await response.json();

                if (data.success) {
                    const select = document.querySelector('#scheduleInterviewForm select[name="application_id"]');
                    const options = data.applications.map(app =>
                        `<option value="${app.id}">${app.job_title} - ${app.applicant_name}</option>`
                    ).join('');
                    select.innerHTML += options;
                }
            } catch (error) {
                console.error('Error loading applications:', error);
            }
        }

        async function scheduleInterview() {
            const form = document.getElementById('scheduleInterviewForm');
            const formData = new FormData(form);

            try {
                const response = await fetch('php/api/interview-api.php?action=schedule', {
                    method: 'POST',
                    body: JSON.stringify(Object.fromEntries(formData)),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    showNotification('Interview scheduled successfully!', 'success');
                    closeScheduleInterviewModal();
                    loadEmployerInterviews();
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error scheduling interview:', error);
                showNotification('Error scheduling interview', 'error');
            }
        }

        function editInterview(interviewId) {
            // Implement edit functionality
            showNotification('Edit functionality coming soon!', 'info');
        }

        async function cancelInterview(interviewId) {
            if (!confirm('Are you sure you want to cancel this interview?')) return;

            try {
                const response = await fetch(`php/api/interview-api.php?action=cancel&id=${interviewId}`, {
                    method: 'POST'
                });
                const data = await response.json();

                if (data.success) {
                    showNotification('Interview cancelled successfully!', 'success');
                    loadEmployerInterviews();
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error cancelling interview:', error);
                showNotification('Error cancelling interview', 'error');
            }
        }

        function getStatusColor(status) {
            switch (status) {
                case 'scheduled':
                    return 'info';
                case 'completed':
                    return 'success';
                case 'cancelled':
                    return 'danger';
                case 'rescheduled':
                    return 'warning';
                default:
                    return 'secondary';
            }
        }
    </script>

    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: var(--border-radius);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            margin: auto;
            z-index: 2001;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .modal-header h4 {
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--gray-500);
        }

        .modal-body {
            padding: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    </style>
</body>

</html>