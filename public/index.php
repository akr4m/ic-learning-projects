<?php

/**
 * User Registration Form
 *
 * This file displays the user registration form and handles
 * flash messages for success/error feedback.
 *
 * @package UserRegistrationSystem
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

// Include configuration (also starts session)
require_once __DIR__ . '/../config.php';

// Get flash messages
$successMessage = getFlashMessage('success');
$errorMessage = getFlashMessage('error');
$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];

// Clear session data after retrieving
unset($_SESSION['errors'], $_SESSION['old_input']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(APP_NAME); ?></title>
    <style>
        /* ========================================
           CSS STYLES
           ======================================== */

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Container styles */
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }

        /* Header styles */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        /* Alert message styles */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
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

        /* Form styles */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        input.error {
            border-color: #dc3545;
        }

        /* Error message styles */
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Button styles */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Password requirements list */
        .password-requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 12px;
        }

        .password-requirements h4 {
            color: #333;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .password-requirements ul {
            list-style: none;
            color: #666;
        }

        .password-requirements li {
            padding: 3px 0;
            padding-left: 20px;
            position: relative;
        }

        .password-requirements li::before {
            content: '>';
            position: absolute;
            left: 5px;
            color: #667eea;
        }

        /* Footer styles */
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <h1><?php echo e(APP_NAME); ?></h1>
        <p class="subtitle">Create your account to get started</p>

        <!-- Success Message -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?php echo e($successMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <?php echo e($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST" novalidate>
            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    value="<?php echo e($oldInput['name'] ?? ''); ?>"
                    class="<?php echo isset($errors['name']) ? 'error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['name'])): ?>
                    <p class="error-message"><?php echo e($errors['name']); ?></p>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo e($oldInput['email'] ?? ''); ?>"
                    class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="error-message"><?php echo e($errors['email']); ?></p>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a strong password"
                    class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="error-message"><?php echo e($errors['password']); ?></p>
                <?php endif; ?>

                <!-- Password Requirements Info -->
                <div class="password-requirements">
                    <h4>Password must contain:</h4>
                    <ul>
                        <li>At least 8 characters</li>
                        <li>One uppercase letter (A-Z)</li>
                        <li>One lowercase letter (a-z)</li>
                        <li>One number (0-9)</li>
                        <li>One special character (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit">Create Account</button>
        </form>

        <!-- Footer -->
        <p class="footer">
            Version <?php echo e(APP_VERSION); ?>
        </p>
    </div>
</body>
</html>
