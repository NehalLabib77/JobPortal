<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    header('Location: login.php?redirect=post-job.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'User';
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - JobPortal</title>
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
                    <li><a href="dashboard-employer.php" class="nav-link">Dashboard</a></li>
                </ul>
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="nav-actions">
                    <span style="color: var(--gray-600); margin-right: 15px;">Welcome, <?php echo htmlspecialchars($userName); ?></span>
                    <a href="php/handlers/logout-handler.php" class="btn btn-outline btn-sm">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Post Job Form -->
    <section class="section" style="padding-top: 120px; background: var(--gray-100);">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto;">
                <div class="card">
                    <h2 style="margin-bottom: 30px; text-align: center;">Post a New Job</h2>

                    <form id="postJobForm" action="php/jobs/create-job.php" method="POST">
                        <!-- Job Details -->
                        <h4
                            style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--gray-200);">
                            <i class="fas fa-briefcase"></i> Job Details
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Senior PHP Developer"
                                required>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="1">Technology</option>
                                    <option value="2">Healthcare</option>
                                    <option value="3">Finance</option>
                                    <option value="4">Marketing</option>
                                    <option value="5">Education</option>
                                    <option value="6">Design</option>
                                    <option value="7">Sales</option>
                                    <option value="8">Engineering</option>
                                    <option value="9">Customer Service</option>
                                    <option value="10">Human Resources</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Job Type *</label>
                                <select name="job_type" class="form-control" required>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Experience Level</label>
                                <select name="experience_level" class="form-control">
                                    <option value="entry">Entry Level</option>
                                    <option value="mid">Mid Level</option>
                                    <option value="senior">Senior Level</option>
                                    <option value="lead">Lead/Manager</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Vacancies</label>
                                <input type="number" name="vacancies" class="form-control" value="1" min="1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Job Description *</label>
                            <textarea name="description" class="form-control" rows="6"
                                placeholder="Describe the job role, responsibilities, and what a typical day looks like..."
                                required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Requirements</label>
                            <textarea name="requirements" class="form-control" rows="5"
                                placeholder="List the required skills, qualifications, and experience..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Benefits</label>
                            <textarea name="benefits" class="form-control" rows="4"
                                placeholder="Describe the benefits and perks of working at your company..."></textarea>
                        </div>

                        <!-- Location & Salary -->
                        <h4
                            style="margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 2px solid var(--gray-200);">
                            <i class="fas fa-map-marker-alt"></i> Location & Compensation
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. New York, USA"
                                required>
                        </div>

                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_remote" value="1"> This is a remote position
                            </label>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Minimum Salary</label>
                                <input type="number" name="salary_min" class="form-control" placeholder="e.g. 50000">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Maximum Salary</label>
                                <input type="number" name="salary_max" class="form-control" placeholder="e.g. 80000">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Salary Type</label>
                                <select name="salary_type" class="form-control">
                                    <option value="yearly">Per Year</option>
                                    <option value="monthly">Per Month</option>
                                    <option value="hourly">Per Hour</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Application Deadline</label>
                            <input type="date" name="deadline" class="form-control">
                        </div>

                        <!-- Payment Section -->
                        <h4
                            style="margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 2px solid var(--gray-200);">
                            <i class="fas fa-money-bill-wave"></i> Payment (Job Posting Fee: ৳200)
                        </h4>

                        <div class="payment-info-box"
                            style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 20px; border-radius: var(--border-radius); margin-bottom: 20px; border-left: 4px solid var(--success-color);">
                            <h5 style="margin-bottom: 10px; color: var(--success-color);">
                                <i class="fas fa-info-circle"></i> Payment Required
                            </h5>
                            <p style="margin: 0; color: var(--gray-700);">
                                To publish your job listing, please complete the payment of <strong>৳200 BDT</strong>
                                using one of the methods below.
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select Payment Method *</label>
                            <div class="payment-methods"
                                style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                                <label class="payment-method-card"
                                    style="display: flex; flex-direction: column; align-items: center; padding: 20px; border: 2px solid var(--gray-200); border-radius: var(--border-radius); cursor: pointer; transition: all 0.3s;">
                                    <input type="radio" name="payment_method" value="bkash" style="display: none;"
                                        required>
                                    <div
                                        style="width: 60px; height: 60px; background: #E2136E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                        <span style="color: white; font-weight: bold; font-size: 14px;">bKash</span>
                                    </div>
                                    <strong>bKash</strong>
                                    <small style="color: var(--gray-500);">01712345678</small>
                                </label>

                                <label class="payment-method-card"
                                    style="display: flex; flex-direction: column; align-items: center; padding: 20px; border: 2px solid var(--gray-200); border-radius: var(--border-radius); cursor: pointer; transition: all 0.3s;">
                                    <input type="radio" name="payment_method" value="nagad" style="display: none;">
                                    <div
                                        style="width: 60px; height: 60px; background: #F6A21E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                        <span style="color: white; font-weight: bold; font-size: 14px;">Nagad</span>
                                    </div>
                                    <strong>Nagad</strong>
                                    <small style="color: var(--gray-500);">01812345678</small>
                                </label>

                                <label class="payment-method-card"
                                    style="display: flex; flex-direction: column; align-items: center; padding: 20px; border: 2px solid var(--gray-200); border-radius: var(--border-radius); cursor: pointer; transition: all 0.3s;">
                                    <input type="radio" name="payment_method" value="rocket" style="display: none;">
                                    <div
                                        style="width: 60px; height: 60px; background: #8C3494; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                        <span style="color: white; font-weight: bold; font-size: 14px;">Rocket</span>
                                    </div>
                                    <strong>Rocket</strong>
                                    <small style="color: var(--gray-500);">01912345678</small>
                                </label>
                            </div>
                        </div>

                        <div id="paymentDetails" style="display: none; margin-top: 20px;">
                            <div class="payment-instruction-box" id="paymentInstructions"
                                style="background: var(--gray-100); padding: 20px; border-radius: var(--border-radius); margin-bottom: 20px;">
                                <!-- Instructions will be populated by JS -->
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Your Mobile Number (Payment Sender) *</label>
                                    <input type="text" name="payer_phone" class="form-control" placeholder="01XXXXXXXXX"
                                        pattern="01[0-9]{9}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Your Name (as shown in payment) *</label>
                                    <input type="text" name="payer_name" class="form-control"
                                        placeholder="Enter your name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div style="display: flex; gap: 15px; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg" style="flex: 1;">
                                <i class="fas fa-paper-plane"></i> Pay ৳200 & Publish Job
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="saveDraft()">
                                <i class="fas fa-save"></i> Save as Draft
                            </button>
                        </div>

                        <p style="text-align: center; color: var(--gray-500); margin-top: 15px; font-size: 14px;">
                            <i class="fas fa-lock"></i> Your payment will be verified within 24 hours. Job will be
                            published after verification.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 JobPortal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <style>
        .payment-method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .payment-method-card.selected {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.05);
        }

        .payment-method-card input:checked+div {
            transform: scale(1.1);
        }
    </style>

    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('paymentDetails').style.display = 'block';

                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.classList.remove('selected');
                });
                this.closest('.payment-method-card').classList.add('selected');

                const method = this.value;
                const numbers = {
                    'bkash': '01712345678',
                    'nagad': '01812345678',
                    'rocket': '01912345678'
                };
                const colors = {
                    'bkash': '#E2136E',
                    'nagad': '#F6A21E',
                    'rocket': '#8C3494'
                };
                const names = {
                    'bkash': 'bKash',
                    'nagad': 'Nagad',
                    'rocket': 'Rocket'
                };

                document.getElementById('paymentInstructions').innerHTML = `
                    <h5 style="margin-bottom: 15px; color: ${colors[method]};">
                        <i class="fas fa-wallet"></i> ${names[method]} Payment Instructions
                    </h5>
                    <ol style="margin: 0; padding-left: 20px; color: var(--gray-700);">
                        <li>Open your <strong>${names[method]}</strong> app</li>
                        <li>Go to <strong>"Send Money"</strong></li>
                        <li>Enter this number: <strong style="color: ${colors[method]};">${numbers[method]}</strong></li>
                        <li>Enter amount: <strong>৳200</strong></li>
                        <li>Add reference: <strong>JobPortal</strong></li>
                        <li>Complete the payment</li>
                    </ol>
                    <div style="margin-top: 15px; padding: 15px; background: white; border-radius: var(--border-radius); text-align: center;">
                        <strong>Send ৳200 to:</strong>
                        <div style="font-size: 24px; color: ${colors[method]}; margin-top: 5px;">${numbers[method]}</div>
                    </div>
                `;
            });
        });

        document.getElementById('postJobForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('Please select a payment method');
                return;
            }

            const payerPhone = document.querySelector('input[name="payer_phone"]').value;
            const payerName = document.querySelector('input[name="payer_name"]').value;

            if (!payerPhone || !payerName) {
                alert('Please fill in all payment details');
                return;
            }

            const txnId = 'TXN' + Date.now();
            alert(`Payment submitted successfully!

Amount: ৳200
Method: ${paymentMethod.value.toUpperCase()}
Status: Pending Verification

Your job will be published after payment verification (within 24 hours).
You can track your payment status in your dashboard.`);

            window.location.href = 'dashboard-employer.php';
        });

        function saveDraft() {
            alert('Job saved as draft. You can complete payment later from your dashboard.');
            window.location.href = 'dashboard-employer.php';
        }
    </script>
</body>

</html>