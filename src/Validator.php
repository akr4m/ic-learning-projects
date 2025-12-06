<?php

/**
 * Validator Class
 *
 * Handles input validation for user registration data.
 * Provides methods to validate name, email, and password fields.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */
class Validator
{
    /**
     * Minimum length for user name
     */
    private const MIN_NAME_LENGTH = 2;

    /**
     * Maximum length for user name
     */
    private const MAX_NAME_LENGTH = 100;

    /**
     * Minimum length for password
     */
    private const MIN_PASSWORD_LENGTH = 8;

    /**
     * Maximum length for password
     */
    private const MAX_PASSWORD_LENGTH = 128;

    /**
     * Array to store validation errors
     *
     * @var array
     */
    private array $errors = [];

    /**
     * Get all validation errors
     *
     * @return array Array of error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if validation passed (no errors)
     *
     * @return bool True if no errors, false otherwise
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Clear all validation errors
     *
     * @return void
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Add an error message to the errors array
     *
     * @param string $field   The field name that has the error
     * @param string $message The error message
     * @return void
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    /**
     * Validate all user registration fields
     *
     * Validates name, email, and password in one call
     *
     * @param string $name     User's name
     * @param string $email    User's email
     * @param string $password User's password
     * @return bool True if all validations pass, false otherwise
     */
    public function validateRegistration(
        string $name,
        string $email,
        string $password
    ): bool {
        // Clear previous errors before new validation
        $this->clearErrors();

        // Validate each field
        $this->validateName($name);
        $this->validateEmail($email);
        $this->validatePassword($password);

        // Return true if no errors were added
        return $this->isValid();
    }

    /**
     * Validate user name
     *
     * Checks:
     * - Not empty
     * - Within length limits
     * - Contains only valid characters (letters, spaces, hyphens, apostrophes)
     *
     * @param string $name The name to validate
     * @return bool True if valid, false otherwise
     */
    public function validateName(string $name): bool
    {
        // Trim whitespace
        $name = trim($name);

        // Check if name is empty
        if (empty($name)) {
            $this->addError('name', 'Name is required.');
            return false;
        }

        // Check minimum length
        if (strlen($name) < self::MIN_NAME_LENGTH) {
            $this->addError(
                'name',
                sprintf('Name must be at least %d characters long.', self::MIN_NAME_LENGTH)
            );
            return false;
        }

        // Check maximum length
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $this->addError(
                'name',
                sprintf('Name must not exceed %d characters.', self::MAX_NAME_LENGTH)
            );
            return false;
        }

        // Check for valid characters (letters, spaces, hyphens, apostrophes)
        // This regex allows Unicode letters for international names
        if (!preg_match("/^[\p{L}\s\-']+$/u", $name)) {
            $this->addError(
                'name',
                'Name can only contain letters, spaces, hyphens, and apostrophes.'
            );
            return false;
        }

        return true;
    }

    /**
     * Validate email address
     *
     * Checks:
     * - Not empty
     * - Valid email format using PHP's filter_var
     *
     * @param string $email The email to validate
     * @return bool True if valid, false otherwise
     */
    public function validateEmail(string $email): bool
    {
        // Trim whitespace
        $email = trim($email);

        // Check if email is empty
        if (empty($email)) {
            $this->addError('email', 'Email is required.');
            return false;
        }

        // Validate email format using PHP's built-in filter
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Please enter a valid email address.');
            return false;
        }

        return true;
    }

    /**
     * Validate password
     *
     * Checks:
     * - Not empty
     * - Minimum length of 8 characters
     * - Contains at least one uppercase letter
     * - Contains at least one lowercase letter
     * - Contains at least one number
     * - Contains at least one special character
     *
     * @param string $password The password to validate
     * @return bool True if valid, false otherwise
     */
    public function validatePassword(string $password): bool
    {
        // Check if password is empty
        if (empty($password)) {
            $this->addError('password', 'Password is required.');
            return false;
        }

        // Check minimum length
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->addError(
                'password',
                sprintf('Password must be at least %d characters long.', self::MIN_PASSWORD_LENGTH)
            );
            return false;
        }

        // Check maximum length
        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            $this->addError(
                'password',
                sprintf('Password must not exceed %d characters.', self::MAX_PASSWORD_LENGTH)
            );
            return false;
        }

        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $this->addError(
                'password',
                'Password must contain at least one uppercase letter.'
            );
            return false;
        }

        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $this->addError(
                'password',
                'Password must contain at least one lowercase letter.'
            );
            return false;
        }

        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $this->addError(
                'password',
                'Password must contain at least one number.'
            );
            return false;
        }

        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $this->addError(
                'password',
                'Password must contain at least one special character (!@#$%^&*()_+-=[]{};\':"|,.<>/?).'
            );
            return false;
        }

        return true;
    }

    /**
     * Sanitize a string input
     *
     * Removes potentially dangerous characters and trims whitespace
     *
     * @param string $input The input string to sanitize
     * @return string Sanitized string
     */
    public static function sanitize(string $input): string
    {
        // Trim whitespace from beginning and end
        $input = trim($input);

        // Remove any HTML tags
        $input = strip_tags($input);

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }

    /**
     * Get formatted error messages as HTML
     *
     * Useful for displaying errors in a web form
     *
     * @return string HTML formatted error messages
     */
    public function getErrorsAsHtml(): string
    {
        if ($this->isValid()) {
            return '';
        }

        $html = '<ul class="errors">';
        foreach ($this->errors as $field => $message) {
            $html .= sprintf('<li>%s</li>', htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get the first error message
     *
     * @return string|null First error message or null if no errors
     */
    public function getFirstError(): ?string
    {
        if ($this->isValid()) {
            return null;
        }

        return reset($this->errors);
    }
}
