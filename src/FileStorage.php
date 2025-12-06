<?php

/**
 * FileStorage Class
 *
 * Handles file-based storage operations for user data.
 * Supports both CSV and JSON file formats.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

// Include the User class for type hinting
require_once __DIR__ . '/User.php';

class FileStorage
{
    /**
     * Path to the data storage directory
     *
     * @var string
     */
    private string $dataPath;

    /**
     * Name of the CSV file for storing users
     */
    private const CSV_FILENAME = 'users.csv';

    /**
     * Name of the JSON file for storing users
     */
    private const JSON_FILENAME = 'users.json';

    /**
     * CSV column headers
     */
    private const CSV_HEADERS = ['id', 'name', 'email', 'password', 'created_at'];

    /**
     * Constructor - Initialize the FileStorage with data path
     *
     * @param string $dataPath Path to the data storage directory
     * @throws Exception If directory cannot be created or is not writable
     */
    public function __construct(string $dataPath)
    {
        $this->dataPath = rtrim($dataPath, '/');

        // Ensure the data directory exists
        $this->ensureDirectoryExists();
    }

    /**
     * Ensure the data directory exists and is writable
     *
     * @throws Exception If directory cannot be created or is not writable
     * @return void
     */
    private function ensureDirectoryExists(): void
    {
        // Create directory if it doesn't exist
        if (!is_dir($this->dataPath)) {
            if (!mkdir($this->dataPath, 0755, true)) {
                throw new Exception("Failed to create data directory: {$this->dataPath}");
            }
        }

        // Check if directory is writable
        if (!is_writable($this->dataPath)) {
            throw new Exception("Data directory is not writable: {$this->dataPath}");
        }
    }

    /**
     * Get the full path to the CSV file
     *
     * @return string Full path to CSV file
     */
    private function getCsvPath(): string
    {
        return $this->dataPath . '/' . self::CSV_FILENAME;
    }

    /**
     * Get the full path to the JSON file
     *
     * @return string Full path to JSON file
     */
    private function getJsonPath(): string
    {
        return $this->dataPath . '/' . self::JSON_FILENAME;
    }

    // ========================================
    // CSV FILE OPERATIONS
    // ========================================

    /**
     * Save a user to the CSV file
     *
     * Appends the user data to the CSV file, creating headers if file is new
     *
     * @param User $user The user object to save
     * @return bool True on success, false on failure
     */
    public function saveUserToCsv(User $user): bool
    {
        $csvPath = $this->getCsvPath();
        $isNewFile = !file_exists($csvPath);

        // Open file for appending
        $handle = fopen($csvPath, 'a');

        if ($handle === false) {
            return false;
        }

        // Write headers if this is a new file
        if ($isNewFile) {
            fputcsv($handle, self::CSV_HEADERS);
        }

        // Prepare user data as array in correct order
        $userData = [
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getCreatedAt()
        ];

        // Write user data to CSV
        $result = fputcsv($handle, $userData);

        // Close file handle
        fclose($handle);

        return $result !== false;
    }

    /**
     * Load all users from the CSV file
     *
     * @return array Array of User objects
     */
    public function loadUsersFromCsv(): array
    {
        $csvPath = $this->getCsvPath();
        $users = [];

        // Return empty array if file doesn't exist
        if (!file_exists($csvPath)) {
            return $users;
        }

        // Open file for reading
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return $users;
        }

        // Read and skip the header row
        $headers = fgetcsv($handle);

        // Read each row and create User objects
        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (empty($row) || count($row) < 5) {
                continue;
            }

            // Map CSV columns to associative array
            $userData = [
                'id'         => $row[0],
                'name'       => $row[1],
                'email'      => $row[2],
                'password'   => $row[3],
                'created_at' => $row[4]
            ];

            // Create User object from array
            $users[] = User::fromArray($userData);
        }

        // Close file handle
        fclose($handle);

        return $users;
    }

    /**
     * Check if an email already exists in the CSV file
     *
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExistsInCsv(string $email): bool
    {
        $users = $this->loadUsersFromCsv();

        foreach ($users as $user) {
            if (strtolower($user->getEmail()) === strtolower($email)) {
                return true;
            }
        }

        return false;
    }

    // ========================================
    // JSON FILE OPERATIONS
    // ========================================

    /**
     * Save a user to the JSON file
     *
     * Loads existing users, adds new user, and saves back to file
     *
     * @param User $user The user object to save
     * @return bool True on success, false on failure
     */
    public function saveUserToJson(User $user): bool
    {
        $jsonPath = $this->getJsonPath();

        // Load existing users from JSON
        $users = $this->loadUsersFromJson();

        // Add new user to array
        $users[] = $user;

        // Convert all users to arrays for JSON encoding
        $usersArray = array_map(function (User $u) {
            return $u->toArray();
        }, $users);

        // Encode to JSON with pretty printing for readability
        $json = json_encode(
            $usersArray,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        if ($json === false) {
            return false;
        }

        // Write to file
        $result = file_put_contents($jsonPath, $json, LOCK_EX);

        return $result !== false;
    }

    /**
     * Load all users from the JSON file
     *
     * @return array Array of User objects
     */
    public function loadUsersFromJson(): array
    {
        $jsonPath = $this->getJsonPath();
        $users = [];

        // Return empty array if file doesn't exist
        if (!file_exists($jsonPath)) {
            return $users;
        }

        // Read file contents
        $json = file_get_contents($jsonPath);

        if ($json === false) {
            return $users;
        }

        // Decode JSON to array
        $usersArray = json_decode($json, true);

        if (!is_array($usersArray)) {
            return $users;
        }

        // Create User objects from arrays
        foreach ($usersArray as $userData) {
            if (is_array($userData) && isset($userData['id'])) {
                $users[] = User::fromArray($userData);
            }
        }

        return $users;
    }

    /**
     * Check if an email already exists in the JSON file
     *
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExistsInJson(string $email): bool
    {
        $users = $this->loadUsersFromJson();

        foreach ($users as $user) {
            if (strtolower($user->getEmail()) === strtolower($email)) {
                return true;
            }
        }

        return false;
    }

    // ========================================
    // COMBINED OPERATIONS
    // ========================================

    /**
     * Save a user to both CSV and JSON files
     *
     * @param User $user The user object to save
     * @return bool True if both saves succeed, false otherwise
     */
    public function saveUser(User $user): bool
    {
        $csvResult = $this->saveUserToCsv($user);
        $jsonResult = $this->saveUserToJson($user);

        return $csvResult && $jsonResult;
    }

    /**
     * Check if an email exists in either CSV or JSON file
     *
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $email): bool
    {
        return $this->emailExistsInCsv($email) || $this->emailExistsInJson($email);
    }

    /**
     * Find a user by email
     *
     * Searches in JSON file first (faster for lookups)
     *
     * @param string $email Email to search for
     * @return User|null User object if found, null otherwise
     */
    public function findUserByEmail(string $email): ?User
    {
        $users = $this->loadUsersFromJson();

        foreach ($users as $user) {
            if (strtolower($user->getEmail()) === strtolower($email)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Find a user by ID
     *
     * @param string $id User ID to search for
     * @return User|null User object if found, null otherwise
     */
    public function findUserById(string $id): ?User
    {
        $users = $this->loadUsersFromJson();

        foreach ($users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Get the count of registered users
     *
     * @return int Number of registered users
     */
    public function getUserCount(): int
    {
        return count($this->loadUsersFromJson());
    }

    /**
     * Delete all user data (useful for testing)
     *
     * WARNING: This permanently deletes all user data!
     *
     * @return bool True on success
     */
    public function deleteAllData(): bool
    {
        $csvDeleted = true;
        $jsonDeleted = true;

        if (file_exists($this->getCsvPath())) {
            $csvDeleted = unlink($this->getCsvPath());
        }

        if (file_exists($this->getJsonPath())) {
            $jsonDeleted = unlink($this->getJsonPath());
        }

        return $csvDeleted && $jsonDeleted;
    }
}
