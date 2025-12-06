<?php

/**
 * Registration Handler
 *
 * Processes the user registration form submission.
 * Validates input, creates user, and saves to CSV/JSON files.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

// Include configuration (starts session and includes autoloader)
require_once __DIR__ . '/../config.php';

// Include required classes
require_once SRC_PATH . '/Validator.php';
require_once SRC_PATH . '/FileStorage.php';
require_once SRC_PATH . '/User.php';

/**
 * Main registration handler class
 *
 * Processes registration requests and handles all validation and storage
 */
class RegistrationHandler
{
    /**
     * Validator instance for input validation
     *
     * @var Validator
     */
    private Validator $validator;

    /**
     * FileStorage instance for data persistence
     *
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * Constructor - Initialize handler with dependencies
     */
    public function __construct()
    {
        $this->validator = new Validator();
        $this->storage = new FileStorage(DATA_PATH);
    }

    /**
     * Handle the registration request
     *
     * Main entry point for processing registration form submissions
     *
     * @return void
     */
    public function handle(): void
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request method.');
            return;
        }

        // Get and sanitize form input
        $name = $this->getInput('name');
        $email = $this->getInput('email');
        $password = $_POST['password'] ?? ''; // Don't sanitize password (may remove valid characters)

        // Store old input for form repopulation (excluding password)
        $_SESSION['old_input'] = [
            'name'  => $name,
            'email' => $email
        ];

        // Validate all inputs
        if (!$this->validator->validateRegistration($name, $email, $password)) {
            $this->redirectWithErrors($this->validator->getErrors());
            return;
        }

        // Check if email already exists
        if ($this->storage->emailExists($email)) {
            $this->redirectWithErrors([
                'email' => 'This email address is already registered.'
            ]);
            return;
        }

        // Create new User object (password will be hashed automatically)
        try {
            $user = new User($name, $email, $password);

            // Save user to both CSV and JSON files
            if ($this->storage->saveUser($user)) {
                // Clear old input on success
                unset($_SESSION['old_input']);

                // Redirect with success message
                $this->redirectWithSuccess(
                    'Registration successful! Welcome, ' . $user->getName() . '!'
                );
            } else {
                $this->redirectWithError('Failed to save user data. Please try again.');
            }
        } catch (Exception $e) {
            // Log error in debug mode
            if (DEBUG_MODE) {
                error_log('Registration error: ' . $e->getMessage());
            }

            $this->redirectWithError('An error occurred during registration. Please try again.');
        }
    }

    /**
     * Get and sanitize input from POST data
     *
     * @param string $key The input field name
     * @return string Sanitized input value
     */
    private function getInput(string $key): string
    {
        $value = $_POST[$key] ?? '';
        return Validator::sanitize($value);
    }

    /**
     * Redirect to registration form with success message
     *
     * @param string $message Success message to display
     * @return void
     */
    private function redirectWithSuccess(string $message): void
    {
        setFlashMessage('success', $message);
        redirect('index.php');
    }

    /**
     * Redirect to registration form with error message
     *
     * @param string $message Error message to display
     * @return void
     */
    private function redirectWithError(string $message): void
    {
        setFlashMessage('error', $message);
        redirect('index.php');
    }

    /**
     * Redirect to registration form with validation errors
     *
     * @param array $errors Array of field-specific error messages
     * @return void
     */
    private function redirectWithErrors(array $errors): void
    {
        $_SESSION['errors'] = $errors;
        redirect('index.php');
    }
}

// ========================================
// EXECUTE REGISTRATION HANDLER
// ========================================

// Create handler instance and process the request
$handler = new RegistrationHandler();
$handler->handle();
