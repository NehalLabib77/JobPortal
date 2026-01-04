<?php

$dbPath = __DIR__ . '/jobportal.db';

if (file_exists($dbPath)) {
    unlink($dbPath);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec('PRAGMA foreign_keys = ON;');

    echo "Creating SQLite database...\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            user_type TEXT CHECK(user_type IN ('candidate', 'employer', 'admin')) DEFAULT 'candidate',
            phone VARCHAR(20),
            avatar VARCHAR(255) DEFAULT 'default.png',
            bio TEXT,
            location VARCHAR(100),
            website VARCHAR(255),
            linkedin VARCHAR(255),
            resume VARCHAR(255),
            skills TEXT,
            experience TEXT,
            education TEXT,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS companies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            name VARCHAR(150) NOT NULL,
            slug VARCHAR(150) UNIQUE,
            logo VARCHAR(255) DEFAULT 'company-default.png',
            description TEXT,
            industry VARCHAR(100),
            company_size VARCHAR(50),
            founded_year INTEGER,
            website VARCHAR(255),
            location VARCHAR(150),
            email VARCHAR(100),
            phone VARCHAR(20),
            is_verified INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Companies table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE,
            icon VARCHAR(50),
            description TEXT,
            job_count INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Categories table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS jobs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            category_id INTEGER,
            title VARCHAR(200) NOT NULL,
            slug VARCHAR(200) UNIQUE,
            description TEXT NOT NULL,
            requirements TEXT,
            benefits TEXT,
            job_type TEXT CHECK(job_type IN ('full-time', 'part-time', 'contract', 'freelance', 'internship')) DEFAULT 'full-time',
            experience_level TEXT CHECK(experience_level IN ('entry', 'mid', 'senior', 'lead')) DEFAULT 'entry',
            salary_min DECIMAL(10,2),
            salary_max DECIMAL(10,2),
            salary_type TEXT CHECK(salary_type IN ('hourly', 'monthly', 'yearly')) DEFAULT 'yearly',
            location VARCHAR(150),
            is_remote INTEGER DEFAULT 0,
            vacancies INTEGER DEFAULT 1,
            deadline DATE,
            status TEXT CHECK(status IN ('active', 'closed', 'draft')) DEFAULT 'active',
            views INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        )
    ");
    echo "✓ Jobs table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS applications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            job_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            cover_letter TEXT,
            resume VARCHAR(255),
            status TEXT CHECK(status IN ('pending', 'reviewed', 'shortlisted', 'rejected', 'hired')) DEFAULT 'pending',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE (job_id, user_id)
        )
    ");
    echo "✓ Applications table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS saved_jobs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            job_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
            UNIQUE (user_id, job_id)
        )
    ");
    echo "✓ Saved Jobs table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sender_id INTEGER NOT NULL,
            receiver_id INTEGER NOT NULL,
            subject VARCHAR(200),
            message TEXT NOT NULL,
            is_read INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Messages table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS payments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            job_id INTEGER,
            transaction_id VARCHAR(100) UNIQUE,
            amount DECIMAL(10,2) NOT NULL DEFAULT 200.00,
            currency VARCHAR(10) DEFAULT 'BDT',
            payment_method TEXT CHECK(payment_method IN ('bkash', 'nagad', 'rocket', 'card', 'bank')) DEFAULT 'bkash',
            payment_status TEXT CHECK(payment_status IN ('pending', 'completed', 'failed', 'refunded')) DEFAULT 'pending',
            payer_phone VARCHAR(20),
            payer_name VARCHAR(100),
            payment_reference VARCHAR(100),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
        )
    ");
    echo "✓ Payments table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS interviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            application_id INTEGER NOT NULL,
            interviewer_id INTEGER NOT NULL,
            candidate_id INTEGER NOT NULL,
            job_id INTEGER NOT NULL,
            interview_date DATETIME NOT NULL,
            interview_type TEXT CHECK(interview_type IN ('phone', 'video', 'in_person')) DEFAULT 'video',
            platform VARCHAR(100),
            meeting_link VARCHAR(500),
            notes TEXT,
            status TEXT CHECK(status IN ('scheduled', 'completed', 'cancelled', 'rescheduled')) DEFAULT 'scheduled',
            feedback TEXT,
            rating INTEGER CHECK(rating >= 1 AND rating <= 5),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
            FOREIGN KEY (interviewer_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Interviews table created\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Site settings table created\n";

    $pdo->exec("
        INSERT INTO site_settings (setting_key, setting_value) VALUES
        ('job_posting_fee', '200'),
        ('currency', 'BDT'),
        ('bkash_number', '01712345678'),
        ('nagad_number', '01812345678'),
        ('rocket_number', '01912345678')
    ");
    echo "✓ Default settings inserted\n";

    $pdo->exec("
        INSERT INTO categories (name, slug, icon, description) VALUES
        ('Technology', 'technology', 'fa-laptop-code', 'Software, IT, and Tech jobs'),
        ('Healthcare', 'healthcare', 'fa-heartbeat', 'Medical and Healthcare positions'),
        ('Finance', 'finance', 'fa-chart-line', 'Banking, Finance, and Accounting'),
        ('Marketing', 'marketing', 'fa-bullhorn', 'Marketing and Advertising roles'),
        ('Education', 'education', 'fa-graduation-cap', 'Teaching and Education jobs'),
        ('Design', 'design', 'fa-palette', 'Graphic and UI/UX Design'),
        ('Sales', 'sales', 'fa-handshake', 'Sales and Business Development'),
        ('Engineering', 'engineering', 'fa-cogs', 'Engineering and Manufacturing'),
        ('Customer Service', 'customer-service', 'fa-headset', 'Support and Customer Service'),
        ('Human Resources', 'human-resources', 'fa-users', 'HR and Recruitment')
    ");
    echo "✓ Categories inserted\n";

    $pdo->exec("
        INSERT INTO users (name, email, password, user_type) VALUES
        ('Admin', 'admin@jobportal.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
    ");
    echo "✓ Admin user created (admin@jobportal.com / password)\n";

    $pdo->exec("
        INSERT INTO users (name, email, password, user_type, location) VALUES
        ('John Smith', 'employer@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'Dhaka, Bangladesh')
    ");
    echo "✓ Employer user created (employer@test.com / password)\n";

    $pdo->exec("
        INSERT INTO companies (user_id, name, slug, description, industry, company_size, location, email) VALUES
        (2, 'Tech Solutions Inc', 'tech-solutions-inc', 'Leading technology company providing innovative solutions', 'Technology', '50-200', 'Dhaka, Bangladesh', 'hr@techsolutions.com')
    ");
    echo "✓ Sample company created\n";

    $pdo->exec("
        INSERT INTO users (name, email, password, user_type, location, skills) VALUES
        ('Jane Doe', 'candidate@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', 'Chittagong, Bangladesh', 'PHP, JavaScript, MySQL, HTML, CSS')
    ");
    echo "✓ Candidate user created (candidate@test.com / password)\n";

    $deadline = date('Y-m-d', strtotime('+30 days'));
    $pdo->exec("
        INSERT INTO jobs (company_id, user_id, category_id, title, slug, description, requirements, job_type, experience_level, salary_min, salary_max, location, is_remote, deadline, status) VALUES
        (1, 2, 1, 'Senior PHP Developer', 'senior-php-developer', 'We are looking for an experienced PHP Developer to join our team. You will be responsible for developing and maintaining web applications.', 'Minimum 5 years of PHP experience\nStrong knowledge of MySQL\nExperience with Laravel or similar frameworks\nGood communication skills', 'full-time', 'senior', 80000, 120000, 'Dhaka, Bangladesh', 1, '$deadline', 'active'),
        (1, 2, 1, 'Frontend Developer', 'frontend-developer', 'Join our dynamic team as a Frontend Developer. Work with modern technologies to create stunning user interfaces.', 'Experience with React or Vue.js\nStrong HTML, CSS, JavaScript skills\nResponsive design knowledge\nGit version control', 'full-time', 'mid', 60000, 90000, 'Remote', 1, '$deadline', 'active'),
        (1, 2, 6, 'UI/UX Designer', 'ui-ux-designer', 'We need a creative UI/UX Designer to design beautiful and functional interfaces for our products.', 'Proficiency in Figma or Sketch\nStrong portfolio\nUser research experience\nPrototyping skills', 'full-time', 'mid', 55000, 85000, 'Dhaka, Bangladesh', 0, '$deadline', 'active'),
        (1, 2, 4, 'Digital Marketing Specialist', 'digital-marketing-specialist', 'Looking for a Digital Marketing Specialist to drive our online presence and marketing campaigns.', 'SEO/SEM experience\nSocial media marketing\nGoogle Analytics\nContent creation skills', 'full-time', 'entry', 45000, 65000, 'Chittagong, Bangladesh', 1, '$deadline', 'active')
    ");
    echo "✓ Sample jobs inserted\n";

    $pdo->exec("
        INSERT INTO payments (user_id, job_id, transaction_id, amount, currency, payment_method, payment_status, payer_phone, payer_name, created_at) VALUES
        (2, 1, 'TXN202512290001', 200.00, 'BDT', 'bkash', 'completed', '01712345678', 'John Smith', '2025-12-29 10:00:00'),
        (2, 2, 'TXN202512280002', 200.00, 'BDT', 'nagad', 'completed', '01812345678', 'John Smith', '2025-12-28 14:30:00'),
        (2, 3, 'TXN202512250003', 200.00, 'BDT', 'bkash', 'completed', '01712345678', 'John Smith', '2025-12-25 09:15:00'),
        (2, 4, 'TXN202512200004', 200.00, 'BDT', 'rocket', 'completed', '01912345678', 'John Smith', '2025-12-20 16:45:00')
    ");
    echo "✓ Sample payments inserted\n";

    $pdo->exec("
        INSERT INTO applications (job_id, user_id, cover_letter, status, created_at) VALUES
        (1, 3, 'I am very interested in the Senior PHP Developer position. With 5+ years of experience in PHP development and expertise in Laravel, I believe I would be a great fit for your team.', 'shortlisted', '2025-12-27 09:00:00'),
        (2, 3, 'I would love to work as a Frontend Developer at your company. My experience with React and modern JavaScript frameworks aligns perfectly with your requirements.', 'pending', '2025-12-26 11:30:00'),
        (3, 3, 'As a UI/UX Designer with a strong portfolio, I am excited about the opportunity to contribute to your design team and create amazing user experiences.', 'reviewed', '2025-12-25 14:15:00')
    ");
    echo "✓ Sample applications inserted\n";

    $futureDate1 = date('Y-m-d H:i:s', strtotime('+2 days'));
    $futureDate2 = date('Y-m-d H:i:s', strtotime('+5 days'));
    $pastDate = date('Y-m-d H:i:s', strtotime('-1 day'));

    $pdo->exec("
        INSERT INTO interviews (application_id, interviewer_id, candidate_id, job_id, interview_date, interview_type, platform, meeting_link, notes, status, created_at) VALUES
        (1, 2, 3, 1, '$futureDate1', 'video', 'Zoom', 'https://zoom.us/j/123456789', 'Technical interview focusing on PHP, Laravel, and database design. Please prepare your portfolio and be ready for coding challenges.', 'scheduled', '2025-12-28 10:00:00'),
        (1, 2, 3, 1, '$pastDate', 'phone', 'Phone', '', 'Initial screening call completed. Candidate showed good technical knowledge and communication skills.', 'completed', '2025-12-26 15:00:00'),
        (3, 2, 3, 3, '$futureDate2', 'in_person', 'Office', '', 'Design portfolio review and creative assessment. Please bring your laptop and design tools.', 'scheduled', '2025-12-27 16:30:00')
    ");
    echo "✓ Sample interviews inserted\n";

    echo "\n========================================\n";
    echo "SQLite database created successfully!\n";
    echo "Location: " . $dbPath . "\n";
    echo "========================================\n";
    echo "\nDemo Credentials:\n";
    echo "Admin: admin@jobportal.com / password\n";
    echo "Employer: employer@test.com / password\n";
    echo "Candidate: candidate@test.com / password\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
