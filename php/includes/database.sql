
CREATE DATABASE IF NOT EXISTS jobportal;
USE jobportal;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('candidate', 'employer', 'admin') DEFAULT 'candidate',
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
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE,
    logo VARCHAR(255) DEFAULT 'company-default.png',
    description TEXT,
    industry VARCHAR(100),
    company_size VARCHAR(50),
    founded_year INT,
    website VARCHAR(255),
    location VARCHAR(150),
    email VARCHAR(100),
    phone VARCHAR(20),
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE,
    icon VARCHAR(50),
    description TEXT,
    job_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    user_id INT NOT NULL,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE,
    description TEXT NOT NULL,
    requirements TEXT,
    benefits TEXT,
    job_type ENUM('full-time', 'part-time', 'contract', 'freelance', 'internship') DEFAULT 'full-time',
    experience_level ENUM('entry', 'mid', 'senior', 'lead') DEFAULT 'entry',
    salary_min DECIMAL(10,2),
    salary_max DECIMAL(10,2),
    salary_type ENUM('hourly', 'monthly', 'yearly') DEFAULT 'yearly',
    location VARCHAR(150),
    is_remote TINYINT(1) DEFAULT 0,
    vacancies INT DEFAULT 1,
    deadline DATE,
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    cover_letter TEXT,
    resume VARCHAR(255),
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'hired') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, user_id)
);


CREATE TABLE IF NOT EXISTS saved_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_saved (user_id, job_id)
);


CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT,
    transaction_id VARCHAR(100) UNIQUE,
    amount DECIMAL(10,2) NOT NULL DEFAULT 200.00,
    currency VARCHAR(10) DEFAULT 'BDT',
    payment_method ENUM('bkash', 'nagad', 'rocket', 'card', 'bank') DEFAULT 'bkash',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payer_phone VARCHAR(20),
    payer_name VARCHAR(100),
    payment_reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO site_settings (setting_key, setting_value) VALUES
('job_posting_fee', '200'),
('currency', 'BDT'),
('bkash_number', '01XXXXXXXXX'),
('nagad_number', '01XXXXXXXXX'),
('rocket_number', '01XXXXXXXXX');

-- Insert Default Categories
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
('Human Resources', 'human-resources', 'fa-users', 'HR and Recruitment');


INSERT INTO users (name, email, password, user_type) VALUES
('Admin', 'admin@jobportal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO users (name, email, password, user_type, location) VALUES
('John Smith', 'employer@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'New York, USA');

INSERT INTO companies (user_id, name, slug, description, industry, company_size, location, email) VALUES
(2, 'Tech Solutions Inc', 'tech-solutions-inc', 'Leading technology company providing innovative solutions', 'Technology', '50-200', 'New York, USA', 'hr@techsolutions.com');

INSERT INTO users (name, email, password, user_type, location, skills) VALUES
('Jane Doe', 'candidate@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate', 'Los Angeles, USA', 'PHP, JavaScript, MySQL, HTML, CSS');

INSERT INTO jobs (company_id, user_id, category_id, title, slug, description, requirements, job_type, experience_level, salary_min, salary_max, location, is_remote, deadline) VALUES
(1, 2, 1, 'Senior PHP Developer', 'senior-php-developer', 'We are looking for an experienced PHP Developer to join our team. You will be responsible for developing and maintaining web applications.', 'Minimum 5 years of PHP experience\nStrong knowledge of MySQL\nExperience with Laravel or similar frameworks\nGood communication skills', 'full-time', 'senior', 80000, 120000, 'New York, USA', 1, DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
(1, 2, 1, 'Frontend Developer', 'frontend-developer', 'Join our dynamic team as a Frontend Developer. Work with modern technologies to create stunning user interfaces.', 'Experience with React or Vue.js\nStrong HTML, CSS, JavaScript skills\nResponsive design knowledge\nGit version control', 'full-time', 'mid', 60000, 90000, 'Remote', 1, DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
(1, 2, 6, 'UI/UX Designer', 'ui-ux-designer', 'We need a creative UI/UX Designer to design beautiful and functional interfaces for our products.', 'Proficiency in Figma or Sketch\nStrong portfolio\nUser research experience\nPrototyping skills', 'full-time', 'mid', 55000, 85000, 'New York, USA', 0, DATE_ADD(CURDATE(), INTERVAL 25 DAY)),
(1, 2, 4, 'Digital Marketing Specialist', 'digital-marketing-specialist', 'Looking for a Digital Marketing Specialist to drive our online presence and marketing campaigns.', 'SEO/SEM experience\nSocial media marketing\nGoogle Analytics\nContent creation skills', 'full-time', 'entry', 45000, 65000, 'Los Angeles, USA', 1, DATE_ADD(CURDATE(), INTERVAL 20 DAY));
