# Absensi API

Absensi API is a simple and efficient API for managing attendance records. It is built with Laravel and provides features for user authentication, attendance tracking, and administrative tools.

## Features

- **Authentication**: User registration and login using Sanctum.
- **Attendance Management**: Check-in, check-out, and view attendance history.
- **Admin Tools**: Access and export attendance data for administrators.
- **Secure**: Middleware for authentication and role-based access control.

## Routes Overview

### Public Routes
- `POST /register` - Register a new user.
- `POST /login` - Log in and obtain an authentication token.

### Authenticated Routes (Requires Sanctum)
- `POST /checkin` - Record a check-in.
- `POST /checkout` - Record a check-out.
- `GET /history` - View attendance history.

### Admin Routes (Requires Admin Role)
- `GET /admin/attendances` - View all attendance records.
- `GET /admin/attendances/export` - Export attendance data.

## Quick Start

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/absensi-api.git
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
4. Run migrations:
   ```bash
   php artisan migrate
   ```
5. Serve the application:
   ```bash
   php artisan serve
   ```

## Contributing

Contributions are welcome! Feel free to submit issues or pull requests to improve this project.

