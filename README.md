# Government Document Typing Course System 1 (ระบบวิชาพิมพ์หนังสือราชการ 1)

This is a web-based typing practice system for educational institutions, specializing in Thai government document formats. It provides a platform for students to practice typing and for teachers to grade and track progress.

## Features
- **Student Portal:**
    - Practice typing with real-time WPM and Accuracy feedback.
    - Submit assignments.
    - View grade history and teacher feedback.
- **Admin Portal:**
    - Manage Assignments (CRUD).
    - Manage Students.
    - Review Submissions and Give Scores/Feedback.
    - Generate PDF Reports for printing.
- **Authentication:**
    - Role-based access control (Admin/Student).
    - Secure login system.

## Requirements
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL Database

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd official-system
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Edit `.env` to match your database credentials.*

5. **Database Migration & Seeding**
   Run migrations and seed the database with initial data (Admin/Student users and sample assignments).
   ```bash
   php artisan migrate --seed --class=TypingSeeder
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```

7. **Run the Application**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://localhost:8000`.

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@example.com` | `password` |
| **Student** | `student@example.com` | `password` |

## Fonts
The PDF generation uses **TH Sarabun New**. Ensure the font files are located in `public/fonts/`.

## License
MIT License.
