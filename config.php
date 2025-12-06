<?php

/**
 * Configuration File
 *
 * Contains all configuration settings for the User Registration System.
 * Includes paths, database credentials, and application settings.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

// ========================================
// PATH CONFIGURATION
// ========================================

/**
 * Base directory of the application
 */
define('BASE_PATH', __DIR__);

/**
 * Source directory containing PHP classes
 */
define('SRC_PATH', BASE_PATH . '/src');

/**
 * Data directory for storing CSV/JSON files
 */
define('DATA_PATH', BASE_PATH . '/data');

/**
 * Public directory for web-accessible files
 */
define('PUBLIC_PATH', BASE_PATH . '/public');

// ========================================
// APPLICATION SETTINGS
// ========================================

/**
 * Application name
 */
define('APP_NAME', 'User Registration System');

/**
 * Application version
 */
define('APP_VERSION', '1.0.0');

/**
 * Enable or disable debug mode
 * Set to false in production!
 */
define('DEBUG_MODE', true);

// ========================================
// ERROR HANDLING
// ========================================

// Set error reporting based on debug mode
if (DEBUG_MODE) {
    // Show all errors in development
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    // Hide errors in production (log them instead)
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', BASE_PATH . '/logs/error.log');
}

// ========================================
// SESSION CONFIGURATION
// ========================================

/**
 * Configure and start session securely
 * Only set ini values if session is not already active
 */
if (session_status() === PHP_SESSION_NONE) {
    // Session cookie settings for security
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_samesite', 'Strict');

    // Start the session
    session_start();
}

// ========================================
// AUTO-LOADER FUNCTION
// ========================================

/**
 * Simple autoloader for loading classes from src directory
 *
 * @param string $className The name of the class to load
 * @return void
 */
spl_autoload_register(function (string $className): void {
    $classFile = SRC_PATH . '/' . $className . '.php';

    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// ========================================
// HELPER FUNCTIONS
// ========================================

/**
 * Redirect to a URL
 *
 * @param string $url The URL to redirect to
 * @return void
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Get a flash message from session and remove it
 *
 * @param string $key The message key
 * @return string|null The message or null if not found
 */
function getFlashMessage(string $key): ?string
{
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Set a flash message in session
 *
 * @param string $key     The message key
 * @param string $message The message content
 * @return void
 */
function setFlashMessage(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

/**
 * Escape output for HTML display
 *
 * @param string $string The string to escape
 * @return string Escaped string safe for HTML output
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
