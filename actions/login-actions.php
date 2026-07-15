<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Login Action
 * ============================================================================
 *
 * Processes login requests submitted from the login page.
 * Authenticates users using the shared loginUser() function and redirects
 * them according to their assigned role.
 * ============================================================================
 */

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/auth/login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Remove expired student accounts before authentication
|--------------------------------------------------------------------------
*/

deleteExpiredUsers();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Please enter your email and password.';
    header('Location: ../views/auth/login.php');
    exit;
}

if (!loginUser($email, $password)) {
    $_SESSION['error'] = 'Invalid email or password.';
    header('Location: ../views/auth/login.php');
    exit;
}

if ($_SESSION['role'] === ROLE_ADMIN) {
    header('Location: ../views/admin/dashboard.php');
    exit;
}

header('Location: ../views/user/home.php');
exit;