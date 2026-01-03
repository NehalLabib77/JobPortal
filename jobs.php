<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';
require_once 'php/includes/functions.php';

// Check if user is logged in to show appropriate navigation
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $_SESSION['user_type'] ?? null;
$userName = $_SESSION['user_name'] ?? 'User';

// Get search filters
$filters = [];
if (!empty($_GET['keyword'])) $filters['keyword'] = $_GET['keyword'];
if (!empty($_GET['location'])) $filters['location'] = $_GET['location'];
if (!empty($_GET['category'])) $filters['category'] = $_GET['category'];

$jobs = getJobs($filters);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs - JobPortal</title>
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
                    <li><a href="jobs.php" class="nav-link active">Find Jobs</a></li>
                    <li><a href="companies.html" class="nav-link">Companies</a></li>
                    <li><a href="about.html" class="nav-link">About</a></li>
                    <li><a href="contact.html" class="nav-link">Contact</a></li>
                </ul>

                <div class="nav-actions" id="navActions">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($userType === 'candidate'): ?>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <a href="dashboard-candidate.php" class="btn btn-outline btn-sm">
                                    <i class="fas fa-user"></i> Dashboard
                                </a>
                                <span style="color: var(--gray-600);">Welcome, <?php echo htmlspecialchars($userName); ?></span>
                                <a href="php/handlers/logout-handler.php" class="btn btn-outline btn-sm">Logout</a>
                            </div>
                        <?php elseif ($userType === 'employer'): ?>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <a href="post-job.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Post Job
                                </a>
                                <a href="dashboard-employer.php" class="btn btn-outline btn-sm">
                                    <i class="fas fa-building"></i> Dashboard
                                </a>
                                <span style="color: var(--gray-600);">Welcome, <?php echo htmlspecialchars($userName); ?></span>
                                <a href="php/handlers/logout-handler.php" class="btn btn-outline btn-sm">Logout</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline btn-sm">Login</a>
                        <a href="register.html" class="btn btn-primary btn-sm">Register</a>
                    <?php endif; ?>
                </div>

                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <section
        style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); padding: 120px 0 60px; color: white;">
        <div class="container">
            <h1 style="color: white;">Find Your Perfect Job</h1>
            <p style="opacity: 0.9;">Browse through thousands of job opportunities</p>
        </div>
    </section>

    <!-- Search & Filters -->
    <section style="background: white; padding: 30px 0; border-bottom: 1px solid var(--gray-200);">
        <div class="container">
            <form class="search-form" id="jobSearchForm" action="jobs.php" method="GET">
                <div class="form-group">
                    <input type="text" name="keyword" id="keyword" class="form-control"
                        placeholder="Job title or keyword" value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="location" id="location" class="form-control" placeholder="Location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <select name="category" id="category" class="form-control">
                        <option value="">All Categories</option>
                        <option value="technology" <?php echo ($_GET['category'] ?? '') === 'technology' ? 'selected' : ''; ?>>Technology</option>
                        <option value="healthcare" <?php echo ($_GET['category'] ?? '') === 'healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                        <option value="finance" <?php echo ($_GET['category'] ?? '') === 'finance' ? 'selected' : ''; ?>>Finance</option>
                        <option value="marketing" <?php echo ($_GET['category'] ?? '') === 'marketing' ? 'selected' : ''; ?>>Marketing</option>
                        <option value="education" <?php echo ($_GET['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Education</option>
                        <option value="design" <?php echo ($_GET['category'] ?? '') === 'design' ? 'selected' : ''; ?>>Design</option>
                        <option value="sales" <?php echo ($_GET['category'] ?? '') === 'sales' ? 'selected' : ''; ?>>Sales</option>
                        <option value="engineering" <?php echo ($_GET['category'] ?? '') === 'engineering' ? 'selected' : ''; ?>>Engineering</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </section>

    <!-- Jobs Listing -->
    <section class="section" style="background: var(--gray-100);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
                <!-- Sidebar Filters -->
                <aside>
                    <div class="card">
                        <h4 style="margin-bottom: 20px;">Filters</h4>

                        <div class="form-group">
                            <label class="form-label">Job Type</label>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="job_type" value="full-time"> Full-time
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="job_type" value="part-time"> Part-time
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="job_type" value="contract"> Contract
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="job_type" value="freelance"> Freelance
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="job_type" value="internship"> Internship
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Experience Level</label>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="experience" value="entry"> Entry Level
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="experience" value="mid"> Mid Level
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="experience" value="senior"> Senior Level
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="experience" value="lead"> Lead/Manager
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Salary Range</label>
                            <select class="form-control" name="salary">
                                <option value="">Any Salary</option>
                                <option value="0-30000">৳0 - ৳30,000</option>
                                <option value="30000-50000">৳30,000 - ৳50,000</option>
                                <option value="50000-80000">৳50,000 - ৳80,000</option>
                                <option value="80000-100000">৳80,000 - ৳100,000</option>
                                <option value="100000+">৳100,000+</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="remote" value="1"> Remote Only
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary" style="width: 100%;" onclick="applyFilters()">
                            Apply Filters
                        </button>
                    </div>
                </aside>

                <!-- Jobs List -->
                <div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <p style="margin: 0;"><strong id="jobCount">24</strong> jobs found</p>
                        <select class="form-control" style="width: auto;" id="sortBy">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="salary-high">Salary: High to Low</option>
                            <option value="salary-low">Salary: Low to High</option>
                        </select>
                    </div>

                    <div class="job-list" id="jobsList">
                        <!-- Job 1 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=1">Senior PHP Developer</a></h4>
                                    <span class="company-name">Tech Solutions Inc</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="1">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</span>
                                <span><i class="fas fa-clock"></i> Full-time</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳80k - ৳120k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Full-time</span>
                                <span class="tag tag-level">Senior</span>
                                <span class="tag tag-remote">Remote</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                We are looking for an experienced PHP Developer to join our team. You will be
                                responsible for developing and maintaining web applications...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 2 days ago</span>
                                <a href="job-single.php?id=1" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job 2 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=2">Frontend Developer</a></h4>
                                    <span class="company-name">Digital Agency Co</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="2">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Remote</span>
                                <span><i class="fas fa-clock"></i> Full-time</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳60k - ৳90k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Full-time</span>
                                <span class="tag tag-level">Mid-level</span>
                                <span class="tag tag-remote">Remote</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                Join our dynamic team as a Frontend Developer. Work with modern technologies to create
                                stunning user interfaces...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 3 days ago</span>
                                <a href="job-single.php?id=2" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job 3 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=3">UI/UX Designer</a></h4>
                                    <span class="company-name">Creative Studio</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="3">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Chittagong, Bangladesh</span>
                                <span><i class="fas fa-clock"></i> Full-time</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳55k - ৳85k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Full-time</span>
                                <span class="tag tag-level">Mid-level</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                We need a creative UI/UX Designer to design beautiful and functional interfaces for our
                                products...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 1 week ago</span>
                                <a href="job-single.php?id=3" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job 4 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=4">Digital Marketing Specialist</a></h4>
                                    <span class="company-name">Growth Marketing</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="4">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Sylhet, Bangladesh</span>
                                <span><i class="fas fa-clock"></i> Full-time</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳45k - ৳65k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Full-time</span>
                                <span class="tag tag-level">Entry</span>
                                <span class="tag tag-remote">Hybrid</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                Looking for a Digital Marketing Specialist to drive our online presence and marketing
                                campaigns...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 5 days ago</span>
                                <a href="job-single.php?id=4" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job 5 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=5">Data Analyst</a></h4>
                                    <span class="company-name">Data Insights Ltd</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="5">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Rajshahi, Bangladesh</span>
                                <span><i class="fas fa-clock"></i> Full-time</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳50k - ৳70k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Full-time</span>
                                <span class="tag tag-level">Entry</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                We're seeking a Data Analyst to help us make data-driven decisions and provide
                                insights...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 1 day ago</span>
                                <a href="job-single.php?id=5" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job 6 -->
                        <div class="job-card">
                            <div class="job-header">
                                <div class="company-logo">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="job-info" style="flex: 1;">
                                    <h4><a href="job-single.php?id=6">Mobile App Developer</a></h4>
                                    <span class="company-name">AppWorks Inc</span>
                                </div>
                                <button class="btn btn-outline btn-sm save-job" data-id="6">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> Khulna, Bangladesh</span>
                                <span><i class="fas fa-clock"></i> Contract</span>
                                <span><i class="fas fa-bangladeshi-taka-sign"></i> ৳90k - ৳130k/yr</span>
                            </div>
                            <div class="job-tags">
                                <span class="tag tag-type">Contract</span>
                                <span class="tag tag-level">Senior</span>
                                <span class="tag tag-remote">Remote</span>
                            </div>
                            <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 15px;">
                                Looking for an experienced Mobile App Developer to build innovative iOS and Android
                                applications...
                            </p>
                            <div class="job-footer">
                                <span class="job-date"><i class="far fa-clock"></i> 4 days ago</span>
                                <a href="job-single.php?id=6" class="btn btn-primary btn-sm">Apply Now</a>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">
                        <button class="btn btn-secondary btn-sm" disabled>&laquo; Previous</button>
                        <button class="btn btn-primary btn-sm">1</button>
                        <button class="btn btn-secondary btn-sm">2</button>
                        <button class="btn btn-secondary btn-sm">3</button>
                        <button class="btn btn-secondary btn-sm">Next &raquo;</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Job<span style="color: var(--primary-color);">Portal</span></h4>
                    <p>Your trusted partner in finding the perfect job or the ideal candidate.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>For Candidates</h4>
                    <ul>
                        <li><a href="jobs.php">Browse Jobs</a></li>
                        <li><a href="companies.html">Browse Companies</a></li>
                        <li><a href="dashboard-candidate.php">Dashboard</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>For Employers</h4>
                    <ul>
                        <li><a href="post-job.html">Post a Job</a></li>
                        <li><a href="dashboard-employer.php">Dashboard</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 JobPortal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>

</html>