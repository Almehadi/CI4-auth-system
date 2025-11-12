# CodeIgniter 4 Authentication System

A complete authentication system for CodeIgniter 4 with employee management, user groups, and login security.

## Features

- Employee information management
- User accounts with role-based permissions
- User groups (Admin, Manager, Employee)
- Login attempts tracking
- Account locking after failed attempts
- Secure password hashing
- Session management
- Bootstrap-responsive UI

## Installation

1. Extract this ZIP file to your CodeIgniter 4 project
2. Run database migrations: `php spark migrate`
3. Configure your database in `app/Config/Database.php`
4. Start the server: `php spark serve`
5. Visit `http://localhost:8080/login`

## Default User Groups

- **Administrator**: Full system access
- **Manager**: Department manager access  
- **Employee**: Regular employee access

## Database Tables

- `employees` - Employee information
- `user_groups` - Role definitions
- `user_accounts` - User credentials
- `login_attempts` - Security tracking

## Usage

1. First, add employees via `/add-employee`
2. Create user accounts via `/register`
3. Login with credentials at `/login`
4. Access dashboard at `/dashboard`

## Security Features

- Password hashing with bcrypt
- Account locking after 5 failed attempts
- Login attempt tracking
- CSRF protection
- Session security