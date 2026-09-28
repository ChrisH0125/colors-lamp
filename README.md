# COLORS LAMP Application

COLORS is a small educational web application built with the LAMP stack. A user can log in, add color names to a MySQL database, and search the colors associated with their account. This repository reorganizes and documents the application originally completed during the COLORS lab.

## Technologies

- Linux-compatible hosting
- Apache or the PHP development server
- MySQL
- PHP 8.1 or newer with the `mysqli` extension
- HTML, CSS, and vanilla JavaScript

## Project structure

```text
colors-lamp/
|-- api/                 PHP API endpoints and shared configuration
|-- database/            Database schema
|-- public/              Browser-facing HTML, CSS, JavaScript, and images
|-- .env.example         Required environment variable names
|-- .gitignore
|-- LICENSE.md
`-- README.md
```

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/ChrisH0125/colors-lamp.git
cd colors-lamp
```

### 2. Create the database

Run the included schema as a MySQL administrator:

```bash
mysql -u root -p < database/schema.sql
```

Create a dedicated database user. Replace the example password with a strong local value and do not commit it:

```sql
CREATE USER 'colors_app_user'@'localhost' IDENTIFIED BY 'choose-a-strong-password';
GRANT SELECT, INSERT ON colors_app.* TO 'colors_app_user'@'localhost';
FLUSH PRIVILEGES;
```

The lab application does not include registration, so add a test user directly. The existing lab schema stores the password as text; use test-only credentials:

```sql
USE colors_app;
INSERT INTO Users (FirstName, LastName, Login, Password)
VALUES ('Test', 'User', 'testuser', 'test-password');
```

### 3. Configure the database connection

The API reads its database settings from environment variables. `.env.example` lists the required names, but the application does not automatically load a `.env` file.

For a local shell session:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_NAME=colors_app
export DB_USER=colors_app_user
export DB_PASSWORD='your-local-password'
```

For Apache, define the same variables in a local virtual-host or server configuration that is not committed to this repository, then restart Apache.

### 4. Run the application

From the repository root, start PHP's development server:

```bash
php -S localhost:8000
```

Open [http://localhost:8000/public/](http://localhost:8000/public/) and log in with the test user you inserted. The frontend calls the PHP endpoints in `api/` using same-origin requests.

For an Apache deployment, place or clone the project under the web root and browse to its `public/` directory. Ensure Apache allows PHP execution and passes the required environment variables to PHP.

## API overview

All endpoints accept `POST` requests with JSON bodies:

- `api/Login.php` verifies a username and password.
- `api/AddColor.php` adds a color for a user.
- `api/SearchColors.php` returns matching colors for a user.

Database queries use prepared statements, and database credentials are supplied through environment variables rather than source code.

## Assumptions and limitations

- This is a course lab application intended for local or controlled demonstration use, not production deployment.
- The original lab data model stores passwords as plain text. A production application should use `password_hash()` and `password_verify()`.
- The browser stores basic user identity data in a cookie and sends the user ID to the API. There is no server-side session, authorization layer, CSRF protection, or rate limiting.
- The application assumes the frontend and API are served from the same origin.
- A user must be inserted directly into MySQL because the lab does not include registration.

## AI usage

AI assistance was used to reorganize the already completed COLORS lab into a public repository, improve documentation and accessibility, replace hardcoded database credentials with environment-based configuration, and review the code for common security issues. The original application behavior and LAMP implementation were completed before this repository-organization assignment.

## License

This project is available under the [MIT License](LICENSE.md).

