<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';

// Check if user is logged in and is a candidate
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'candidate') {
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
    <title>Candidate Dashboard - JobPortal</title>
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
                    <li><a href="jobs.php?user=candidate" class="nav-link">Find Jobs</a></li>
                    <li><a href="companies.html?user=candidate" class="nav-link">Companies</a></li>
                </ul>

                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="nav-actions">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="color: var(--gray-600);">Welcome, <?php echo htmlspecialchars($userName); ?></span>
                        <a href="php/handlers/logout-handler.php" class="btn btn-outline btn-sm">Logout</a>
                    </div>
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
                            <i class="fas fa-user"></i>
                        </div>
                        <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($userName); ?></h4>
                        <p style="color: var(--gray-500); font-size: 14px; margin: 0;">Job Seeker</p>
                    </div>

                    <nav class="sidebar-nav">
                        <a href="#" class="active" onclick="showTab('overview')">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a href="#" onclick="showTab('profile')">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <a href="#" onclick="showTab('applications')">
                            <i class="fas fa-file-alt"></i> My Applications
                        </a>
                        <a href="#" onclick="showTab('interviews')">
                            <i class="fas fa-calendar-alt"></i> My Interviews
                        </a>
                        <a href="#" onclick="showTab('saved')">
                            <i class="fas fa-heart"></i> Saved Jobs
                        </a>
                        <a href="#" onclick="showTab('messages')">
                            <i class="fas fa-envelope"></i> Messages
                            <span class="badge badge-danger" style="margin-left: auto;">3</span>
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
                            <a href="jobs.php?user=candidate" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Find Jobs
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <i class="fas fa-file-alt"></i>
                                <h3>12</h3>
                                <p>Applications</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-eye"></i>
                                <h3>48</h3>
                                <p>Profile Views</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-heart"></i>
                                <h3>8</h3>
                                <p>Saved Jobs</p>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-envelope"></i>
                                <h3>3</h3>
                                <p>Messages</p>
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
                                            <th>Job</th>
                                            <th>Company</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="job-single.php">Senior PHP Developer</a></td>
                                            <td>Tech Solutions Inc</td>
                                            <td>Dec 27, 2025</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td><a href="#">Frontend Developer</a></td>
                                            <td>Digital Agency Co</td>
                                            <td>Dec 25, 2025</td>
                                            <td><span class="badge badge-info">Reviewed</span></td>
                                        </tr>
                                        <tr>
                                            <td><a href="#">UI/UX Designer</a></td>
                                            <td>Creative Studio</td>
                                            <td>Dec 20, 2025</td>
                                            <td><span class="badge badge-success">Shortlisted</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Profile Completion -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Profile Completion</h4>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Profile Strength</span>
                                    <span style="color: var(--primary-color); font-weight: 600;">75%</span>
                                </div>
                                <div
                                    style="background: var(--gray-200); height: 10px; border-radius: 5px; overflow: hidden;">
                                    <div style="background: var(--primary-color); height: 100%; width: 75%;"></div>
                                </div>
                            </div>
                            <ul style="font-size: 14px; color: var(--gray-600);">
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success"></i> Basic
                                    info completed</li>
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success"></i> Resume
                                    uploaded</li>
                                <li style="margin-bottom: 10px;"><i class="fas fa-times-circle text-danger"></i> Add
                                    work experience</li>
                                <li><i class="fas fa-times-circle text-danger"></i> Add education details</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div id="profile-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>My Profile</h2>
                        </div>

                        <form id="profileForm">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="Jane Doe">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="jane@example.com" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" placeholder="Enter phone number">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" class="form-control" value="Los Angeles, USA">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" rows="4"
                                    placeholder="Tell us about yourself..."></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Skills</label>
                                <input type="text" class="form-control" value="PHP, JavaScript, MySQL, HTML, CSS"
                                    placeholder="Separate skills with commas">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Resume</label>
                                <input type="file" class="form-control" accept=".pdf,.doc,.docx">
                                <small style="color: var(--gray-500);">Current: resume_jane_doe.pdf</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Applications Tab -->
                    <div id="applications-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>My Applications</h2>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select class="form-control" style="width: auto;" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="reviewed">Reviewed</option>
                                    <option value="shortlisted">Shortlisted</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="hired">Hired</option>
                                </select>
                            </div>
                        </div>

                        <div class="applications-container" id="applicationsContainer">
                            <!-- Applications will be loaded here -->
                        </div>
                    </div>

                    <!-- Interviews Tab -->
                    <div id="interviews-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>My Interviews</h2>
                        </div>

                        <div class="interviews-container">
                            <div class="interviews-list" id="interviewsList">
                                <!-- Interviews will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Saved Jobs Tab -->
                    <div id="saved-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Saved Jobs</h2>
                        </div>

                        <div class="job-list">
                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"><i class="fas fa-building"></i></div>
                                    <div class="job-info" style="flex: 1;">
                                        <h4><a href="job-single.php">Senior PHP Developer</a></h4>
                                        <span class="company-name">Tech Solutions Inc</span>
                                    </div>
                                    <button class="btn btn-outline btn-sm text-danger">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="job-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</span>
                                    <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳80k - ৳120k</span>
                                </div>
                                <div class="job-footer">
                                    <span class="job-date">Saved 2 days ago</span>
                                    <a href="job-single.php" class="btn btn-primary btn-sm">Apply Now</a>
                                </div>
                            </div>

                            <div class="job-card">
                                <div class="job-header">
                                    <div class="company-logo"><i class="fas fa-code"></i></div>
                                    <div class="job-info" style="flex: 1;">
                                        <h4><a href="#">Frontend Developer</a></h4>
                                        <span class="company-name">Digital Agency Co</span>
                                    </div>
                                    <button class="btn btn-outline btn-sm text-danger">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="job-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> Remote</span>
                                    <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳60k - ৳90k</span>
                                </div>
                                <div class="job-footer">
                                    <span class="job-date">Saved 5 days ago</span>
                                    <a href="#" class="btn btn-primary btn-sm">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Tab -->
                    <div id="messages-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Messages</h2>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--primary-color);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <h5 style="margin-bottom: 5px;">Tech Solutions Inc</h5>
                                        <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 10px;">
                                            Thank you for applying. We would like to schedule an interview...
                                        </p>
                                        <span style="font-size: 12px; color: var(--gray-400);">Dec 28, 2025</span>
                                    </div>
                                    <span class="badge badge-info">New</span>
                                </div>
                            </div>

                            <div class="card" style="margin-bottom: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <h5 style="margin-bottom: 5px;">Creative Studio</h5>
                                        <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 10px;">
                                            Congratulations! You've been shortlisted for the UI/UX Designer position...
                                        </p>
                                        <span style="font-size: 12px; color: var(--gray-400);">Dec 22, 2025</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div id="settings-tab" class="hidden">
                        <div class="dashboard-header">
                            <h2>Settings</h2>
                        </div>

                        <div class="card">
                            <h4>Change Password</h4>
                            <form style="margin-top: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>

                        <div class="card">
                            <h4>Email Notifications</h4>
                            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" checked> Job recommendations
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" checked> Application updates
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" checked> Messages from employers
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox"> Newsletter
                                </label>
                            </div>
                            <button class="btn btn-primary" style="margin-top: 20px;">Save Preferences</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Update sidebar active state
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Load applications if applications tab is selected
            if (tabName === 'applications') {
                loadApplications();
            }

            // Load interviews if interviews tab is selected
            if (tabName === 'interviews') {
                loadInterviews();
            }
        }

        async function loadApplications() {
            try {
                const statusFilter = document.getElementById('statusFilter').value;
                const url = statusFilter ? `php/api/applications-api.php?action=list&status=${statusFilter}` : 'php/api/applications-api.php?action=list';

                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    displayApplications(data.applications);
                } else {
                    document.getElementById('applicationsContainer').innerHTML = '<p class="text-center text-muted">Failed to load applications</p>';
                }
            } catch (error) {
                console.error('Error loading applications:', error);
                document.getElementById('applicationsContainer').innerHTML = '<p class="text-center text-muted">Error loading applications</p>';
            }
        }

        async function loadInterviews() {
            try {
                const response = await fetch('php/api/interview-api.php?action=list');
                const data = await response.json();

                if (data.success) {
                    displayInterviews(data.interviews);
                } else {
                    document.getElementById('interviewsList').innerHTML = '<p class="text-center text-muted">Failed to load interviews</p>';
                }
            } catch (error) {
                console.error('Error loading interviews:', error);
                document.getElementById('interviewsList').innerHTML = '<p class="text-center text-muted">Error loading interviews</p>';
            }
        }

        // Auto-refresh interviews every 30 seconds
        setInterval(() => {
            if (document.getElementById('interviews-tab') && !document.getElementById('interviews-tab').classList.contains('hidden')) {
                loadInterviews();
            }
        }, 30000);

        function displayApplications(applications) {
            const container = document.getElementById('applicationsContainer');

            if (applications.length === 0) {
                container.innerHTML = '<div class="text-center" style="padding: 40px;"><i class="fas fa-file-alt fa-3x text-muted"></i><h4 style="margin-top: 20px; color: var(--gray-500);">No applications found</h4><p style="color: var(--gray-400);">You haven\'t applied to any jobs yet.</p></div>';
                return;
            }

            const html = applications.map(app => `
                <div class="application-card" style="background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 24px; margin-bottom: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                    <div class="application-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; color: var(--primary-color); font-size: 18px;">
                                <a href="job-single.php?id=${app.job_id}" style="text-decoration: none; color: inherit;">${app.job_title}</a>
                            </h4>
                            <p style="margin: 0; color: var(--gray-600); font-size: 16px; font-weight: 500;">${app.company_name}</p>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-${getStatusColor(app.status)}" style="font-size: 12px; padding: 6px 12px; border-radius: 20px;">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span>
                        </div>
                    </div>

                    <div class="application-meta" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-calendar" style="color: var(--gray-400);"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Applied Date</div>
                                <div style="font-size: 14px; color: var(--gray-700);">${new Date(app.created_at).toLocaleDateString()}</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-map-marker-alt" style="color: var(--gray-400);"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Location</div>
                                <div style="font-size: 14px; color: var(--gray-700);">${app.location}</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="color: var(--gray-400);"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Job Type</div>
                                <div style="font-size: 14px; color: var(--gray-700);">${app.job_type}</div>
                            </div>
                        </div>
                    </div>

                    ${app.cover_letter ? `<div class="application-cover-letter" style="background: var(--gray-50); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                        <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Cover Letter</div>
                        <p style="margin: 0; color: var(--gray-700); line-height: 1.5;">${app.cover_letter.length > 200 ? app.cover_letter.substring(0, 200) + '...' : app.cover_letter}</p>
                    </div>` : ''}

                    <div class="application-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="job-single.php?id=${app.job_id}" class="btn btn-outline btn-sm" style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-eye"></i> View Job
                        </a>
                        ${app.status === 'shortlisted' ? `<button class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 8px;" onclick="checkInterviews(${app.id})">
                            <i class="fas fa-calendar-alt"></i> Check Interview
                        </button>` : ''}
                    </div>
                </div>
            `).join('');

            container.innerHTML = html;
        }

        function checkInterviews(applicationId) {
            // Switch to interviews tab and filter by this application
            showTab('interviews');
            // Could add filtering logic here
        }

        // Add event listener for status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            if (document.getElementById('applications-tab').classList.contains('hidden') === false) {
                loadApplications();
            }
        });

        function displayInterviews(interviews) {
            const container = document.getElementById('interviewsList');

            if (interviews.length === 0) {
                container.innerHTML = '<div class="text-center" style="padding: 40px;"><i class="fas fa-calendar-alt fa-3x text-muted"></i><h4 style="margin-top: 20px; color: var(--gray-500);">No interviews scheduled</h4><p style="color: var(--gray-400);">You will see your upcoming interviews here.</p></div>';
                return;
            }

            const html = interviews.map(interview => `
                <div class="interview-card" style="border: 1px solid var(--gray-200); border-radius: 8px; padding: 20px; margin-bottom: 15px; background: white;">
                    <div class="interview-header" style="display: flex; justify-content: between; align-items: flex-start; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0; color: var(--primary-color);">${interview.job_title}</h4>
                            <p style="margin: 5px 0; color: var(--gray-600);">${interview.company_name || interview.candidate_name}</p>
                        </div>
                        <span class="badge badge-${getStatusColor(interview.status)}">${interview.status}</span>
                    </div>

                    <div class="interview-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
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

                    ${interview.meeting_link ? `<div style="margin-bottom: 15px;"><a href="${interview.meeting_link}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Join Meeting</a></div>` : ''}

                    ${interview.notes ? `<div class="interview-notes" style="background: var(--gray-50); padding: 10px; border-radius: 4px; margin-bottom: 15px;"><strong>Notes:</strong> ${interview.notes}</div>` : ''}

                    ${interview.feedback ? `<div class="interview-feedback" style="background: var(--gray-50); padding: 10px; border-radius: 4px;"><strong>Feedback:</strong> ${interview.feedback} ${interview.rating ? `(${interview.rating}/5 stars)` : ''}</div>` : ''}
                </div>
            `).join('');

            container.innerHTML = html;
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
                case 'pending':
                    return 'warning';
                case 'reviewed':
                    return 'info';
                case 'shortlisted':
                    return 'success';
                case 'rejected':
                    return 'danger';
                case 'hired':
                    return 'success';
                default:
                    return 'secondary';
            }
        }
    </script>
</body>

</html>