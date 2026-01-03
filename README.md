# JobPortal

A professional job portal application built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

- User registration and authentication (Candidates, Employers, Admins)
- Job posting and management
- Job search and filtering
- Application management
- Interview scheduling
- Payment integration for job postings
- Admin dashboard for site management
- Responsive design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependencies)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/jobportal.git
   cd jobportal
   ```

2. Install PHP dependencies (if any):
   ```bash
   composer install
   ```

3. Set up the database:
   - Create a MySQL database named `jobportal`
   - Import the database schema from `php/includes/database.sql`

4. Configure database connection:
   - Edit `php/includes/config.php` with your database credentials

5. Set up web server:
   - Point your web server to the project root directory
   - Ensure `uploads/` directory is writable

6. Access the application:
   - Open your browser and navigate to the application URL
   - Default admin login: admin@jobportal.com / password

## Usage

### For Candidates:
- Register as a candidate
- Search and apply for jobs
- Track application status
- Manage profile and resume

### For Employers:
- Register as an employer
- Post jobs (requires payment)
- Manage job postings
- Review applications
- Schedule interviews

### For Admins:
- Manage users and companies
- Moderate job postings
- View site statistics
- Configure site settings

## Security Features

- Password hashing with bcrypt
- Prepared statements for SQL queries
- Session management
- Input validation and sanitization
- CSRF protection

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.