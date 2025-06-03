# Web Development Labs

This repository contains my completed lab assignments for the Web Development course, demonstrating progressive skills in web application development.

## Lab 2: Database Interaction Basics

### Files and Implementation:

- **`db.php`**: Database connection configuration file that establishes a connection to MySQL using mysqli.

- **`add_book.php`**: 
  - Provides a form interface for adding new books to the database
  - Includes a dropdown to select authors from existing records
  - Contains form fields for title, genre, and price
  - Submits data to process_book.php

- **`process_book.php`**: 
  - Receives form data from add_book.php
  - Validates input (especially numeric values for price)
  - Uses prepared statements to safely insert book data into the Books table
  - Provides feedback and navigation links after submission

- **`read_books.php`**: 
  - Displays all books in a tabular format
  - Includes book ID, title, author, year, genre, and price
  - Provides edit and delete links for each book entry
  - Contains a link to add new books

- **`update_book.php`** (referenced in read_books.php):
  - Loads existing book data into a form
  - Allows modification of book details
  - Submits changes to the database

- **`delete_book.php`** (referenced in read_books.php):
  - Handles the deletion of book records
  - Includes confirmation before deletion
  - Redirects back to the book listing after deletion

## Lab 3: PHP Basics and Form Handling

### Files and Implementation:

- **`config.php`** (excluded from repository via .gitignore):
  - Contains database credentials and configuration settings
  - Establishes database connection parameters

- **Various form processing files**:
  - Implement server-side validation for user inputs
  - Demonstrate PHP control structures (if/else, loops)
  - Show proper error handling and user feedback
  - Implement security measures like input sanitization

- **Session management files**:
  - Demonstrate PHP session handling
  - Implement user state persistence across pages
  - Show proper session security practices

## Lab 4: Advanced Database Operations

### Files and Implementation:

- **`db_credentials.php`** (excluded from repository via .gitignore):
  - Contains sensitive database connection information
  - Defines constants for database host, username, password, and database name

- **Database interaction files**:
  - Implement more complex SQL queries
  - Demonstrate joins between multiple tables
  - Show advanced filtering and sorting of data
  - Implement pagination for large datasets

- **Data manipulation files**:
  - Handle more complex CRUD operations
  - Implement transaction management
  - Demonstrate proper error handling for database operations

## Lab 5 & 6: OAuth Integration and Security

### Exercise 1: Basic Authentication

- **`login.php`**:
  - Provides traditional username/password login form
  - Validates user credentials against database records
  - Uses password_verify() to check hashed passwords
  - Creates session variables upon successful authentication
  - Displays appropriate error messages for failed login attempts

### Exercise 2: Google OAuth Implementation

- **`config.example.php`**:
  - Template for the actual config.php file
  - Contains placeholders for Google OAuth credentials
  - Includes database connection parameters
  - Shows Google API client initialization code

- **`config.php`** (excluded from repository via .gitignore):
  - Contains actual Google OAuth credentials
  - Includes database connection parameters
  - Initializes the Google API client with proper settings

- **`google_login.php`**:
  - Displays login options including Google OAuth
  - Generates the Google authentication URL
  - Provides links to traditional login and registration

- **`google_callback.php`**:
  - Handles the OAuth callback from Google
  - Authenticates the user with the provided code
  - Retrieves user profile information from Google
  - Checks if the user exists in the database
  - Creates new user accounts for first-time Google logins
  - Establishes user sessions after successful authentication

- **`auth_check.php`**:
  - Provides a reusable function to verify user authentication
  - Redirects unauthenticated users to the login page
  - Can be included in any page requiring authentication

- **`home.php`**:
  - Displays the authenticated user's dashboard
  - Shows different features based on login method
  - Provides navigation to other sections of the application
  - Demonstrates conditional content based on session variables

- **`logout.php`**:
  - Terminates the user session
  - Destroys all session variables
  - Redirects to the login page
  - Handles both traditional and Google OAuth logout

- **`csrf_token.php`**:
  - Implements Cross-Site Request Forgery protection
  - Generates secure random tokens
  - Provides verification functions for form submissions
  - Prevents CSRF attacks on sensitive operations

- **`add_book.php`**:
  - Demonstrates CSRF protection implementation
  - Shows how to secure form submissions
  - Includes the CSRF token in the form
  - Verifies the token on submission

## Security Implementations

### Authentication Security
- Password hashing using PHP's native password_hash() function
- Secure session management with proper session configuration
- OAuth 2.0 implementation following security best practices
- Protection against session fixation attacks

### Data Security
- Prepared statements for all database queries to prevent SQL injection
- Input validation and sanitization before processing
- Output escaping to prevent XSS attacks (using htmlspecialchars())
- CSRF protection on all forms that change state

### Configuration Security
- Sensitive configuration files excluded via .gitignore
- Example configuration templates provided without credentials
- Database credentials kept separate from application code
- Proper file permissions recommended in setup instructions

## Setup Instructions

1. Clone the repository
2. Configure your web server (Apache/Nginx) to serve the files
3. Create necessary database tables using the provided SQL schemas
4. Copy example configuration files and update with your credentials:
   - For Lab4: Copy db_credentials.example.php to db_credentials.php
   - For Lab5and6: Copy config.example.php to config.php
5. For Google OAuth setup:
   - Create a project in Google Cloud Console
   - Configure OAuth consent screen
   - Create OAuth client ID credentials
   - Add authorized redirect URIs
   - Update config.php with your client ID and secret

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Authentication**: Traditional login system and Google OAuth 2.0
- **Package Management**: Composer
- **External Libraries**: Google API Client

## Contact

For any questions regarding this repository, please contact me at [your-email@example.com]
