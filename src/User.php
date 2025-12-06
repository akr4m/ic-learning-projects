<?php

/**
 * User Class
 *
 * This class represents a user entity with encapsulation principles.
 * It handles user data management including password hashing for security.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */
class User
{
    /**
     * User's unique identifier
     *
     * @var string
     */
    private string $id;

    /**
     * User's full name
     *
     * @var string
     */
    private string $name;

    /**
     * User's email address
     *
     * @var string
     */
    private string $email;

    /**
     * User's hashed password
     *
     * @var string
     */
    private string $password;

    /**
     * Timestamp when user was created
     *
     * @var string
     */
    private string $createdAt;

    /**
     * Constructor - Initializes a new User instance
     *
     * @param string      $name     User's full name
     * @param string      $email    User's email address
     * @param string      $password User's plain text password (will be hashed)
     * @param string|null $id       Optional user ID (auto-generated if not provided)
     */
    public function __construct(
        string $name,
        string $email,
        string $password,
        ?string $id = null
    ) {
        // Generate unique ID if not provided
        $this->id = $id ?? $this->generateUniqueId();

        // Set user properties
        $this->name = $name;
        $this->email = $email;

        // Hash the password for security using bcrypt algorithm
        $this->password = $this->hashPassword($password);

        // Set creation timestamp
        $this->createdAt = date('Y-m-d H:i:s');
    }

    /**
     * Generate a unique identifier for the user
     *
     * Uses uniqid() with more entropy and a prefix for uniqueness
     *
     * @return string Unique identifier
     */
    private function generateUniqueId(): string
    {
        return uniqid('user_', true);
    }

    /**
     * Hash a plain text password using bcrypt
     *
     * Uses PHP's password_hash() function with BCRYPT algorithm
     * which automatically handles salt generation
     *
     * @param string $plainPassword The plain text password to hash
     * @return string The hashed password
     */
    private function hashPassword(string $plainPassword): string
    {
        // Using BCRYPT with cost factor of 12 for good security/performance balance
        return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify a plain text password against the stored hash
     *
     * @param string $plainPassword The plain text password to verify
     * @return bool True if password matches, false otherwise
     */
    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }

    // ========================================
    // GETTER METHODS (Encapsulation)
    // ========================================

    /**
     * Get user's unique identifier
     *
     * @return string User ID
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get user's name
     *
     * @return string User's full name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get user's email
     *
     * @return string User's email address
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get user's hashed password
     *
     * Note: This returns the hashed password, never the plain text
     *
     * @return string Hashed password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get user's creation timestamp
     *
     * @return string Creation date and time
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    // ========================================
    // SETTER METHODS (Encapsulation)
    // ========================================

    /**
     * Set user's name
     *
     * @param string $name New name value
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Set user's email
     *
     * @param string $email New email value
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Update user's password
     *
     * Automatically hashes the new password before storing
     *
     * @param string $plainPassword New plain text password
     * @return void
     */
    public function setPassword(string $plainPassword): void
    {
        $this->password = $this->hashPassword($plainPassword);
    }

    /**
     * Set the creation timestamp
     *
     * Used when loading user from storage
     *
     * @param string $createdAt Timestamp string
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Set the hashed password directly
     *
     * Used when loading user from storage (password already hashed)
     *
     * @param string $hashedPassword Already hashed password
     * @return void
     */
    public function setHashedPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    /**
     * Convert user object to associative array
     *
     * Useful for JSON encoding or array operations
     *
     * @return array User data as associative array
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => $this->password,
            'created_at' => $this->createdAt
        ];
    }

    /**
     * Create a User instance from an associative array
     *
     * Static factory method for creating User objects from stored data
     *
     * @param array $data Associative array with user data
     * @return User New User instance
     */
    public static function fromArray(array $data): User
    {
        // Create user with empty password (we'll set hashed password directly)
        $user = new self(
            $data['name'],
            $data['email'],
            '', // Empty password - will be overwritten
            $data['id']
        );

        // Set the already hashed password from storage
        $user->setHashedPassword($data['password']);

        // Set the original creation timestamp
        $user->setCreatedAt($data['created_at']);

        return $user;
    }

    /**
     * String representation of the User object
     *
     * @return string User information (excluding sensitive data)
     */
    public function __toString(): string
    {
        return sprintf(
            "User[id=%s, name=%s, email=%s, created=%s]",
            $this->id,
            $this->name,
            $this->email,
            $this->createdAt
        );
    }
}
