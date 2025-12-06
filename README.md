# PHP User Registration System - ধাপে ধাপে গাইড

## প্রজেক্টের উদ্দেশ্য

এই প্রজেক্টে আমরা শিখব:
- PHP OOP (Class, Object, Encapsulation)
- Input Validation
- Password Hashing (bcrypt)
- File Handling (CSV ও JSON)
- Form Processing
- Session Management

---

## প্রয়োজনীয় উপকরণ

- PHP 8.0 বা তার উপরে
- Code Editor (VS Code / PHPStorm)
- Web Browser
- Terminal / Command Prompt

---

## প্রজেক্ট স্ট্রাকচার

```
project01/
├── config.php              # কনফিগারেশন ফাইল
├── data/                   # ডাটা স্টোরেজ ফোল্ডার
│   ├── users.csv          # (স্বয়ংক্রিয়ভাবে তৈরি হবে)
│   └── users.json         # (স্বয়ংক্রিয়ভাবে তৈরি হবে)
├── public/                 # পাবলিক ফোল্ডার (ওয়েব অ্যাক্সেসযোগ্য)
│   ├── index.php          # রেজিস্ট্রেশন ফর্ম
│   └── register.php       # ফর্ম হ্যান্ডলার
└── src/                    # সোর্স কোড ফোল্ডার
    ├── User.php           # User ক্লাস
    ├── Validator.php      # Validator ক্লাস
    └── FileStorage.php    # FileStorage ক্লাস
```

---

## ধাপ ১: প্রজেক্ট ফোল্ডার তৈরি

Terminal খুলুন এবং নিচের কমান্ডগুলো চালান:

```bash
# প্রজেক্ট ফোল্ডারে যান
cd /path/to/your/projects

# মূল ফোল্ডার তৈরি করুন
mkdir project01
cd project01

# সাব-ফোল্ডার তৈরি করুন
mkdir src
mkdir data
mkdir public
```

**যা শিখলাম:**
- `mkdir` কমান্ড দিয়ে ফোল্ডার তৈরি
- প্রজেক্ট স্ট্রাকচার অর্গানাইজ করা

---

## ধাপ ২: User Class তৈরি (src/User.php)

**ফাইল তৈরি করুন:** `src/User.php`

```php
<?php

/**
 * User Class
 *
 * এই ক্লাসটি একজন ইউজারের ডাটা ম্যানেজ করে।
 * Encapsulation প্রিন্সিপল ব্যবহার করা হয়েছে।
 */
class User
{
    /**
     * ইউজারের ইউনিক আইডি
     * private মানে এই প্রপার্টি শুধু এই ক্লাসের ভেতর থেকে অ্যাক্সেস করা যাবে
     */
    private string $id;

    /**
     * ইউজারের নাম
     */
    private string $name;

    /**
     * ইউজারের ইমেইল
     */
    private string $email;

    /**
     * ইউজারের হ্যাশড পাসওয়ার্ড
     */
    private string $password;

    /**
     * অ্যাকাউন্ট তৈরির সময়
     */
    private string $createdAt;

    /**
     * Constructor - নতুন User অবজেক্ট তৈরি করে
     *
     * @param string      $name     ইউজারের নাম
     * @param string      $email    ইউজারের ইমেইল
     * @param string      $password প্লেইন টেক্সট পাসওয়ার্ড (হ্যাশ হবে)
     * @param string|null $id       ইউজার আইডি (না দিলে অটো জেনারেট)
     */
    public function __construct(
        string $name,
        string $email,
        string $password,
        ?string $id = null
    ) {
        // আইডি না দিলে অটো জেনারেট করো
        $this->id = $id ?? $this->generateUniqueId();

        // প্রপার্টিগুলো সেট করো
        $this->name = $name;
        $this->email = $email;

        // পাসওয়ার্ড হ্যাশ করে সেভ করো
        $this->password = $this->hashPassword($password);

        // বর্তমান সময় সেট করো
        $this->createdAt = date('Y-m-d H:i:s');
    }

    /**
     * ইউনিক আইডি জেনারেট করে
     *
     * @return string ইউনিক আইডি
     */
    private function generateUniqueId(): string
    {
        return uniqid('user_', true);
    }

    /**
     * পাসওয়ার্ড হ্যাশ করে
     *
     * bcrypt অ্যালগরিদম ব্যবহার করে পাসওয়ার্ড সিকিউর করে
     *
     * @param string $plainPassword প্লেইন টেক্সট পাসওয়ার্ড
     * @return string হ্যাশড পাসওয়ার্ড
     */
    private function hashPassword(string $plainPassword): string
    {
        // BCRYPT ব্যবহার করে হ্যাশ করো, cost 12 মানে বেশি সিকিউর
        return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * পাসওয়ার্ড ভেরিফাই করে
     *
     * @param string $plainPassword চেক করার পাসওয়ার্ড
     * @return bool মিললে true, না মিললে false
     */
    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }

    // ========================================
    // GETTER মেথডস (ডাটা পড়ার জন্য)
    // ========================================

    /**
     * আইডি রিটার্ন করে
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * নাম রিটার্ন করে
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * ইমেইল রিটার্ন করে
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * হ্যাশড পাসওয়ার্ড রিটার্ন করে
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * তৈরির সময় রিটার্ন করে
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    // ========================================
    // SETTER মেথডস (ডাটা পরিবর্তনের জন্য)
    // ========================================

    /**
     * নাম সেট করে
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * ইমেইল সেট করে
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * নতুন পাসওয়ার্ড সেট করে (অটো হ্যাশ হবে)
     */
    public function setPassword(string $plainPassword): void
    {
        $this->password = $this->hashPassword($plainPassword);
    }

    /**
     * তৈরির সময় সেট করে (ফাইল থেকে লোড করার সময়)
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * ইতিমধ্যে হ্যাশড পাসওয়ার্ড সেট করে
     */
    public function setHashedPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    /**
     * User অবজেক্টকে Array তে কনভার্ট করে
     *
     * @return array ইউজার ডাটার অ্যারে
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
     * Array থেকে User অবজেক্ট তৈরি করে
     *
     * @param array $data ইউজার ডাটার অ্যারে
     * @return User নতুন User অবজেক্ট
     */
    public static function fromArray(array $data): User
    {
        // খালি পাসওয়ার্ড দিয়ে User তৈরি করো
        $user = new self(
            $data['name'],
            $data['email'],
            '',
            $data['id']
        );

        // স্টোরেজ থেকে আসা হ্যাশড পাসওয়ার্ড সেট করো
        $user->setHashedPassword($data['password']);
        $user->setCreatedAt($data['created_at']);

        return $user;
    }
}
```

**যা শিখলাম:**
- Class ও Object কী
- Private properties (Encapsulation)
- Constructor মেথড
- Getter ও Setter মেথড
- Password hashing (`password_hash`, `password_verify`)
- Static method (`fromArray`)
- Type declarations (string, ?string, void, bool)

---

## ধাপ ৩: Validator Class তৈরি (src/Validator.php)

**ফাইল তৈরি করুন:** `src/Validator.php`

```php
<?php

/**
 * Validator Class
 *
 * ইউজার ইনপুট ভ্যালিডেট করার জন্য এই ক্লাস ব্যবহার করা হয়।
 */
class Validator
{
    /**
     * নামের সর্বনিম্ন দৈর্ঘ্য
     */
    private const MIN_NAME_LENGTH = 2;

    /**
     * নামের সর্বোচ্চ দৈর্ঘ্য
     */
    private const MAX_NAME_LENGTH = 100;

    /**
     * পাসওয়ার্ডের সর্বনিম্ন দৈর্ঘ্য
     */
    private const MIN_PASSWORD_LENGTH = 8;

    /**
     * এরর মেসেজ স্টোর করার অ্যারে
     */
    private array $errors = [];

    /**
     * সব এরর রিটার্ন করে
     *
     * @return array এরর মেসেজের অ্যারে
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * ভ্যালিডেশন পাস করেছে কিনা চেক করে
     *
     * @return bool এরর না থাকলে true
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * সব এরর মুছে ফেলে
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * নতুন এরর যোগ করে
     *
     * @param string $field ফিল্ডের নাম
     * @param string $message এরর মেসেজ
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    /**
     * সব ফিল্ড একসাথে ভ্যালিডেট করে
     *
     * @param string $name ইউজারের নাম
     * @param string $email ইউজারের ইমেইল
     * @param string $password ইউজারের পাসওয়ার্ড
     * @return bool সব ঠিক থাকলে true
     */
    public function validateRegistration(
        string $name,
        string $email,
        string $password
    ): bool {
        // আগের এরর মুছে ফেলো
        $this->clearErrors();

        // প্রতিটি ফিল্ড ভ্যালিডেট করো
        $this->validateName($name);
        $this->validateEmail($email);
        $this->validatePassword($password);

        return $this->isValid();
    }

    /**
     * নাম ভ্যালিডেট করে
     *
     * চেক করে:
     * - খালি কিনা
     * - দৈর্ঘ্য ঠিক আছে কিনা
     * - শুধু অক্ষর, স্পেস, হাইফেন আছে কিনা
     *
     * @param string $name ভ্যালিডেট করার নাম
     * @return bool ভ্যালিড হলে true
     */
    public function validateName(string $name): bool
    {
        // শুরু ও শেষের স্পেস সরাও
        $name = trim($name);

        // খালি কিনা চেক করো
        if (empty($name)) {
            $this->addError('name', 'নাম দিতে হবে।');
            return false;
        }

        // সর্বনিম্ন দৈর্ঘ্য চেক করো
        if (strlen($name) < self::MIN_NAME_LENGTH) {
            $this->addError('name', 'নাম কমপক্ষে ২ অক্ষরের হতে হবে।');
            return false;
        }

        // সর্বোচ্চ দৈর্ঘ্য চেক করো
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $this->addError('name', 'নাম ১০০ অক্ষরের বেশি হতে পারবে না।');
            return false;
        }

        // শুধু অক্ষর, স্পেস, হাইফেন অনুমোদিত
        if (!preg_match("/^[\p{L}\s\-']+$/u", $name)) {
            $this->addError('name', 'নামে শুধু অক্ষর, স্পেস ও হাইফেন থাকতে পারবে।');
            return false;
        }

        return true;
    }

    /**
     * ইমেইল ভ্যালিডেট করে
     *
     * @param string $email ভ্যালিডেট করার ইমেইল
     * @return bool ভ্যালিড হলে true
     */
    public function validateEmail(string $email): bool
    {
        $email = trim($email);

        // খালি কিনা চেক করো
        if (empty($email)) {
            $this->addError('email', 'ইমেইল দিতে হবে।');
            return false;
        }

        // PHP এর বিল্ট-ইন ইমেইল ভ্যালিডেশন ব্যবহার করো
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'সঠিক ইমেইল দিন।');
            return false;
        }

        return true;
    }

    /**
     * পাসওয়ার্ড ভ্যালিডেট করে
     *
     * চেক করে:
     * - খালি কিনা
     * - কমপক্ষে ৮ অক্ষর
     * - একটি বড় হাতের অক্ষর
     * - একটি ছোট হাতের অক্ষর
     * - একটি সংখ্যা
     * - একটি বিশেষ চিহ্ন
     *
     * @param string $password ভ্যালিডেট করার পাসওয়ার্ড
     * @return bool ভ্যালিড হলে true
     */
    public function validatePassword(string $password): bool
    {
        // খালি কিনা চেক করো
        if (empty($password)) {
            $this->addError('password', 'পাসওয়ার্ড দিতে হবে।');
            return false;
        }

        // সর্বনিম্ন দৈর্ঘ্য চেক করো
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->addError('password', 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।');
            return false;
        }

        // বড় হাতের অক্ষর চেক করো
        if (!preg_match('/[A-Z]/', $password)) {
            $this->addError('password', 'পাসওয়ার্ডে কমপক্ষে একটি বড় হাতের অক্ষর (A-Z) থাকতে হবে।');
            return false;
        }

        // ছোট হাতের অক্ষর চেক করো
        if (!preg_match('/[a-z]/', $password)) {
            $this->addError('password', 'পাসওয়ার্ডে কমপক্ষে একটি ছোট হাতের অক্ষর (a-z) থাকতে হবে।');
            return false;
        }

        // সংখ্যা চেক করো
        if (!preg_match('/[0-9]/', $password)) {
            $this->addError('password', 'পাসওয়ার্ডে কমপক্ষে একটি সংখ্যা (0-9) থাকতে হবে।');
            return false;
        }

        // বিশেষ চিহ্ন চেক করো
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $this->addError('password', 'পাসওয়ার্ডে কমপক্ষে একটি বিশেষ চিহ্ন (!@#$%^&*) থাকতে হবে।');
            return false;
        }

        return true;
    }

    /**
     * ইনপুট স্যানিটাইজ করে (নিরাপদ করে)
     *
     * @param string $input স্যানিটাইজ করার ইনপুট
     * @return string নিরাপদ ইনপুট
     */
    public static function sanitize(string $input): string
    {
        // শুরু ও শেষের স্পেস সরাও
        $input = trim($input);

        // HTML ট্যাগ সরাও
        $input = strip_tags($input);

        // বিশেষ অক্ষর এনকোড করো (XSS প্রতিরোধ)
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }
}
```

**যা শিখলাম:**
- Class Constants (`const`)
- Input Validation
- Regular Expressions (`preg_match`)
- `filter_var()` ফাংশন
- XSS প্রতিরোধ (`htmlspecialchars`, `strip_tags`)
- Static method

---

## ধাপ ৪: FileStorage Class তৈরি (src/FileStorage.php)

**ফাইল তৈরি করুন:** `src/FileStorage.php`

```php
<?php

/**
 * FileStorage Class
 *
 * CSV ও JSON ফাইলে ইউজার ডাটা সেভ ও লোড করার জন্য।
 */

require_once __DIR__ . '/User.php';

class FileStorage
{
    /**
     * ডাটা ফোল্ডারের পাথ
     */
    private string $dataPath;

    /**
     * CSV ফাইলের নাম
     */
    private const CSV_FILENAME = 'users.csv';

    /**
     * JSON ফাইলের নাম
     */
    private const JSON_FILENAME = 'users.json';

    /**
     * CSV এর কলাম হেডার
     */
    private const CSV_HEADERS = ['id', 'name', 'email', 'password', 'created_at'];

    /**
     * Constructor - FileStorage তৈরি করে
     *
     * @param string $dataPath ডাটা ফোল্ডারের পাথ
     */
    public function __construct(string $dataPath)
    {
        $this->dataPath = rtrim($dataPath, '/');

        // ডাটা ফোল্ডার আছে কিনা নিশ্চিত করো
        $this->ensureDirectoryExists();
    }

    /**
     * ডাটা ফোল্ডার তৈরি করে (না থাকলে)
     */
    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->dataPath)) {
            mkdir($this->dataPath, 0755, true);
        }
    }

    /**
     * CSV ফাইলের পূর্ণ পাথ রিটার্ন করে
     */
    private function getCsvPath(): string
    {
        return $this->dataPath . '/' . self::CSV_FILENAME;
    }

    /**
     * JSON ফাইলের পূর্ণ পাথ রিটার্ন করে
     */
    private function getJsonPath(): string
    {
        return $this->dataPath . '/' . self::JSON_FILENAME;
    }

    // ========================================
    // CSV ফাইল অপারেশন
    // ========================================

    /**
     * CSV ফাইলে ইউজার সেভ করে
     *
     * @param User $user সেভ করার User অবজেক্ট
     * @return bool সফল হলে true
     */
    public function saveUserToCsv(User $user): bool
    {
        $csvPath = $this->getCsvPath();
        $isNewFile = !file_exists($csvPath);

        // ফাইল খোলো append মোডে (শেষে যোগ করার জন্য)
        $handle = fopen($csvPath, 'a');

        if ($handle === false) {
            return false;
        }

        // নতুন ফাইল হলে হেডার লেখো
        if ($isNewFile) {
            fputcsv($handle, self::CSV_HEADERS);
        }

        // ইউজার ডাটা অ্যারে তৈরি করো
        $userData = [
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getCreatedAt()
        ];

        // CSV এ লেখো
        $result = fputcsv($handle, $userData);

        // ফাইল বন্ধ করো
        fclose($handle);

        return $result !== false;
    }

    /**
     * CSV ফাইল থেকে সব ইউজার লোড করে
     *
     * @return array User অবজেক্টের অ্যারে
     */
    public function loadUsersFromCsv(): array
    {
        $csvPath = $this->getCsvPath();
        $users = [];

        // ফাইল না থাকলে খালি অ্যারে রিটার্ন করো
        if (!file_exists($csvPath)) {
            return $users;
        }

        // ফাইল খোলো পড়ার জন্য
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return $users;
        }

        // প্রথম লাইন (হেডার) স্কিপ করো
        fgetcsv($handle);

        // প্রতিটি লাইন পড়ো
        while (($row = fgetcsv($handle)) !== false) {
            // খালি লাইন স্কিপ করো
            if (empty($row) || count($row) < 5) {
                continue;
            }

            // অ্যারে তৈরি করো
            $userData = [
                'id'         => $row[0],
                'name'       => $row[1],
                'email'      => $row[2],
                'password'   => $row[3],
                'created_at' => $row[4]
            ];

            // User অবজেক্ট তৈরি করো
            $users[] = User::fromArray($userData);
        }

        fclose($handle);

        return $users;
    }

    // ========================================
    // JSON ফাইল অপারেশন
    // ========================================

    /**
     * JSON ফাইলে ইউজার সেভ করে
     *
     * @param User $user সেভ করার User অবজেক্ট
     * @return bool সফল হলে true
     */
    public function saveUserToJson(User $user): bool
    {
        $jsonPath = $this->getJsonPath();

        // আগের সব ইউজার লোড করো
        $users = $this->loadUsersFromJson();

        // নতুন ইউজার যোগ করো
        $users[] = $user;

        // সব ইউজারকে অ্যারেতে কনভার্ট করো
        $usersArray = array_map(function (User $u) {
            return $u->toArray();
        }, $users);

        // JSON এ কনভার্ট করো (সুন্দর ফরম্যাটে)
        $json = json_encode(
            $usersArray,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        if ($json === false) {
            return false;
        }

        // ফাইলে লেখো
        $result = file_put_contents($jsonPath, $json, LOCK_EX);

        return $result !== false;
    }

    /**
     * JSON ফাইল থেকে সব ইউজার লোড করে
     *
     * @return array User অবজেক্টের অ্যারে
     */
    public function loadUsersFromJson(): array
    {
        $jsonPath = $this->getJsonPath();
        $users = [];

        // ফাইল না থাকলে খালি অ্যারে রিটার্ন করো
        if (!file_exists($jsonPath)) {
            return $users;
        }

        // ফাইল পড়ো
        $json = file_get_contents($jsonPath);

        if ($json === false) {
            return $users;
        }

        // JSON ডিকোড করো
        $usersArray = json_decode($json, true);

        if (!is_array($usersArray)) {
            return $users;
        }

        // প্রতিটি ডাটা থেকে User অবজেক্ট তৈরি করো
        foreach ($usersArray as $userData) {
            if (is_array($userData) && isset($userData['id'])) {
                $users[] = User::fromArray($userData);
            }
        }

        return $users;
    }

    // ========================================
    // সাধারণ অপারেশন
    // ========================================

    /**
     * ইউজার CSV ও JSON দুটোতেই সেভ করে
     *
     * @param User $user সেভ করার User অবজেক্ট
     * @return bool দুটোতেই সফল হলে true
     */
    public function saveUser(User $user): bool
    {
        $csvResult = $this->saveUserToCsv($user);
        $jsonResult = $this->saveUserToJson($user);

        return $csvResult && $jsonResult;
    }

    /**
     * ইমেইল আগে থেকে আছে কিনা চেক করে
     *
     * @param string $email চেক করার ইমেইল
     * @return bool থাকলে true
     */
    public function emailExists(string $email): bool
    {
        $users = $this->loadUsersFromJson();

        foreach ($users as $user) {
            if (strtolower($user->getEmail()) === strtolower($email)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ইমেইল দিয়ে ইউজার খুঁজে বের করে
     *
     * @param string $email খোঁজার ইমেইল
     * @return User|null পেলে User, না পেলে null
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
}
```

**যা শিখলাম:**
- File read/write (`fopen`, `fclose`, `file_get_contents`, `file_put_contents`)
- CSV handling (`fgetcsv`, `fputcsv`)
- JSON handling (`json_encode`, `json_decode`)
- `array_map()` ফাংশন
- Anonymous functions (Closure)
- Nullable return type (`?User`)

---

## ধাপ ৫: Configuration File তৈরি (config.php)

**ফাইল তৈরি করুন:** `config.php` (প্রজেক্টের রুটে)

```php
<?php

/**
 * Configuration File
 *
 * প্রজেক্টের সব সেটিংস এখানে থাকে।
 */

// ========================================
// পাথ কনফিগারেশন
// ========================================

/**
 * প্রজেক্টের মূল ফোল্ডার
 */
define('BASE_PATH', __DIR__);

/**
 * সোর্স কোড ফোল্ডার
 */
define('SRC_PATH', BASE_PATH . '/src');

/**
 * ডাটা স্টোরেজ ফোল্ডার
 */
define('DATA_PATH', BASE_PATH . '/data');

/**
 * পাবলিক ফোল্ডার
 */
define('PUBLIC_PATH', BASE_PATH . '/public');

// ========================================
// অ্যাপ্লিকেশন সেটিংস
// ========================================

/**
 * অ্যাপের নাম
 */
define('APP_NAME', 'User Registration System');

/**
 * অ্যাপের ভার্সন
 */
define('APP_VERSION', '1.0.0');

/**
 * ডিবাগ মোড (প্রোডাকশনে false করুন!)
 */
define('DEBUG_MODE', true);

// ========================================
// এরর হ্যান্ডলিং
// ========================================

if (DEBUG_MODE) {
    // ডেভেলপমেন্টে সব এরর দেখাও
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    // প্রোডাকশনে এরর লুকাও
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ========================================
// সেশন কনফিগারেশন
// ========================================

/**
 * সেশন কনফিগার ও স্টার্ট করো
 */
if (session_status() === PHP_SESSION_NONE) {
    // সেশন সিকিউরিটি সেটিংস
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_samesite', 'Strict');

    // সেশন শুরু করো
    session_start();
}

// ========================================
// অটোলোডার
// ========================================

/**
 * ক্লাস অটোমেটিক লোড করার জন্য
 */
spl_autoload_register(function (string $className): void {
    $classFile = SRC_PATH . '/' . $className . '.php';

    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// ========================================
// হেল্পার ফাংশন
// ========================================

/**
 * অন্য পেজে রিডাইরেক্ট করে
 *
 * @param string $url যে URL এ যেতে হবে
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * ফ্ল্যাশ মেসেজ পড়ে (একবার দেখানোর পর মুছে যায়)
 *
 * @param string $key মেসেজের কী
 * @return string|null মেসেজ বা null
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
 * ফ্ল্যাশ মেসেজ সেট করে
 *
 * @param string $key মেসেজের কী
 * @param string $message মেসেজ
 */
function setFlashMessage(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

/**
 * HTML এ নিরাপদে আউটপুট করার জন্য
 *
 * @param string $string এস্কেপ করার স্ট্রিং
 * @return string নিরাপদ স্ট্রিং
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
```

**যা শিখলাম:**
- Constants (`define`)
- Error handling
- Session management
- `spl_autoload_register()` - অটোলোডার
- Flash messages (একবার দেখানোর মেসেজ)
- Helper functions

---

## ধাপ ৬: Registration Form তৈরি (public/index.php)

**ফাইল তৈরি করুন:** `public/index.php`

```php
<?php

/**
 * User Registration Form
 *
 * এই পেজে রেজিস্ট্রেশন ফর্ম দেখায়।
 */

// কনফিগারেশন লোড করো (সেশনও স্টার্ট হবে)
require_once __DIR__ . '/../config.php';

// ফ্ল্যাশ মেসেজ পড়ো
$successMessage = getFlashMessage('success');
$errorMessage = getFlashMessage('error');
$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];

// সেশন ডাটা ক্লিয়ার করো
unset($_SESSION['errors'], $_SESSION['old_input']);

?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(APP_NAME); ?></title>
    <style>
        /* বেসিক রিসেট */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* Alert মেসেজ */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ফর্ম স্টাইল */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        /* পাসওয়ার্ড নিয়ম */
        .password-rules {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }

        .password-rules h4 {
            margin-bottom: 10px;
        }

        .password-rules ul {
            margin-left: 20px;
        }

        .password-rules li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo e(APP_NAME); ?></h1>

        <!-- সফলতার মেসেজ -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?php echo e($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- এরর মেসেজ -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <?php echo e($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- রেজিস্ট্রেশন ফর্ম -->
        <form action="register.php" method="POST">
            <!-- নাম -->
            <div class="form-group">
                <label for="name">পূর্ণ নাম</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="আপনার নাম লিখুন"
                    value="<?php echo e($oldInput['name'] ?? ''); ?>"
                    class="<?php echo isset($errors['name']) ? 'error' : ''; ?>"
                >
                <?php if (isset($errors['name'])): ?>
                    <p class="error-message"><?php echo e($errors['name']); ?></p>
                <?php endif; ?>
            </div>

            <!-- ইমেইল -->
            <div class="form-group">
                <label for="email">ইমেইল</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="আপনার ইমেইল লিখুন"
                    value="<?php echo e($oldInput['email'] ?? ''); ?>"
                    class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="error-message"><?php echo e($errors['email']); ?></p>
                <?php endif; ?>
            </div>

            <!-- পাসওয়ার্ড -->
            <div class="form-group">
                <label for="password">পাসওয়ার্ড</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="একটি শক্তিশালী পাসওয়ার্ড দিন"
                    class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="error-message"><?php echo e($errors['password']); ?></p>
                <?php endif; ?>

                <div class="password-rules">
                    <h4>পাসওয়ার্ডে থাকতে হবে:</h4>
                    <ul>
                        <li>কমপক্ষে ৮ অক্ষর</li>
                        <li>একটি বড় হাতের অক্ষর (A-Z)</li>
                        <li>একটি ছোট হাতের অক্ষর (a-z)</li>
                        <li>একটি সংখ্যা (0-9)</li>
                        <li>একটি বিশেষ চিহ্ন (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>

            <button type="submit">রেজিস্টার করুন</button>
        </form>
    </div>
</body>
</html>
```

**যা শিখলাম:**
- HTML form তৈরি
- PHP ও HTML মিশ্রিত কোড
- Form input handling
- Flash messages দেখানো
- CSS styling
- XSS প্রতিরোধ (`e()` ফাংশন)

---

## ধাপ ৭: Registration Handler তৈরি (public/register.php)

**ফাইল তৈরি করুন:** `public/register.php`

```php
<?php

/**
 * Registration Handler
 *
 * ফর্ম সাবমিশন প্রসেস করে।
 */

// কনফিগারেশন লোড করো
require_once __DIR__ . '/../config.php';

// প্রয়োজনীয় ক্লাস লোড করো
require_once SRC_PATH . '/Validator.php';
require_once SRC_PATH . '/FileStorage.php';
require_once SRC_PATH . '/User.php';

/**
 * RegistrationHandler Class
 *
 * রেজিস্ট্রেশন প্রসেস করার জন্য।
 */
class RegistrationHandler
{
    /**
     * Validator ইনস্ট্যান্স
     */
    private Validator $validator;

    /**
     * FileStorage ইনস্ট্যান্স
     */
    private FileStorage $storage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validator = new Validator();
        $this->storage = new FileStorage(DATA_PATH);
    }

    /**
     * রেজিস্ট্রেশন রিকোয়েস্ট হ্যান্ডল করে
     */
    public function handle(): void
    {
        // শুধু POST রিকোয়েস্ট গ্রহণ করো
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request.');
            return;
        }

        // ইনপুট নাও ও স্যানিটাইজ করো
        $name = $this->getInput('name');
        $email = $this->getInput('email');
        $password = $_POST['password'] ?? '';

        // পুরনো ইনপুট সেভ করো (এরর হলে ফর্মে দেখাতে)
        $_SESSION['old_input'] = [
            'name'  => $name,
            'email' => $email
        ];

        // ভ্যালিডেশন চেক করো
        if (!$this->validator->validateRegistration($name, $email, $password)) {
            $this->redirectWithErrors($this->validator->getErrors());
            return;
        }

        // ইমেইল আগে থেকে আছে কিনা চেক করো
        if ($this->storage->emailExists($email)) {
            $this->redirectWithErrors([
                'email' => 'এই ইমেইল দিয়ে আগেই রেজিস্ট্রেশন করা হয়েছে।'
            ]);
            return;
        }

        // নতুন User তৈরি করো
        try {
            $user = new User($name, $email, $password);

            // CSV ও JSON এ সেভ করো
            if ($this->storage->saveUser($user)) {
                // পুরনো ইনপুট ক্লিয়ার করো
                unset($_SESSION['old_input']);

                // সফলতার মেসেজ দেখাও
                $this->redirectWithSuccess(
                    'রেজিস্ট্রেশন সফল! স্বাগতম, ' . $user->getName() . '!'
                );
            } else {
                $this->redirectWithError('ডাটা সেভ করতে সমস্যা হয়েছে।');
            }
        } catch (Exception $e) {
            $this->redirectWithError('একটি সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        }
    }

    /**
     * ইনপুট নেয় ও স্যানিটাইজ করে
     */
    private function getInput(string $key): string
    {
        $value = $_POST[$key] ?? '';
        return Validator::sanitize($value);
    }

    /**
     * সফলতার মেসেজসহ রিডাইরেক্ট করে
     */
    private function redirectWithSuccess(string $message): void
    {
        setFlashMessage('success', $message);
        redirect('index.php');
    }

    /**
     * এরর মেসেজসহ রিডাইরেক্ট করে
     */
    private function redirectWithError(string $message): void
    {
        setFlashMessage('error', $message);
        redirect('index.php');
    }

    /**
     * ভ্যালিডেশন এররসহ রিডাইরেক্ট করে
     */
    private function redirectWithErrors(array $errors): void
    {
        $_SESSION['errors'] = $errors;
        redirect('index.php');
    }
}

// হ্যান্ডলার চালাও
$handler = new RegistrationHandler();
$handler->handle();
```

**যা শিখলাম:**
- POST request handling
- Form data processing
- Exception handling (`try-catch`)
- Session এ ডাটা স্টোর
- PRG Pattern (Post-Redirect-Get)

---

## ধাপ ৮: প্রজেক্ট টেস্ট করা

### সার্ভার চালু করুন:

```bash
cd /path/to/project01
php -S localhost:8000 -t public
```

### ব্রাউজারে যান:

```
http://localhost:8000
```

### টেস্ট কেস:

1. **খালি ফর্ম সাবমিট করুন** - এরর মেসেজ দেখা যাবে
2. **ভুল ইমেইল দিন** - ইমেইল এরর দেখাবে
3. **দুর্বল পাসওয়ার্ড দিন** - পাসওয়ার্ড এরর দেখাবে
4. **সঠিক ডাটা দিন** - সফলতার মেসেজ দেখাবে
5. **একই ইমেইল আবার দিন** - ডুপ্লিকেট এরর দেখাবে

### সেভ করা ডাটা দেখুন:

```bash
# JSON ফাইল দেখুন
cat data/users.json

# CSV ফাইল দেখুন
cat data/users.csv
```

---

## সারসংক্ষেপ

এই প্রজেক্টে যা শিখলাম:

| বিষয় | ব্যবহৃত কনসেপ্ট |
|-------|----------------|
| **OOP** | Class, Object, Constructor, Methods |
| **Encapsulation** | Private properties, Getters, Setters |
| **Validation** | Input validation, Regular expressions |
| **Security** | Password hashing, XSS prevention, Input sanitization |
| **File Handling** | CSV read/write, JSON read/write |
| **Session** | Flash messages, Form data persistence |
| **Form Processing** | POST handling, PRG pattern |

---

## অতিরিক্ত চ্যালেঞ্জ

প্রজেক্ট শেষ করার পর এগুলো চেষ্টা করতে পারেন:

1. **Login System** - লগইন ফর্ম ও অথেন্টিকেশন যোগ করুন
2. **User List** - সব রেজিস্টার্ড ইউজারের তালিকা দেখান
3. **Delete User** - ইউজার ডিলিট করার অপশন যোগ করুন
4. **Update Profile** - প্রোফাইল আপডেট করার সুবিধা যোগ করুন
5. **Email Verification** - ইমেইল ভেরিফিকেশন সিস্টেম বানান

---

**শুভ কামনা! Happy Coding!**
