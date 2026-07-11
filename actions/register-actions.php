<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Registration Action
 * ============================================================================
 *
 * Processes registration requests submitted from the registration page.
 * Creates a new user account using the shared registerUser() function.
 * ============================================================================
 */

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/auth/register.php');
    exit;
}

if (registerUser($_POST)) {

    $_SESSION['success'] = 'Registration successful. You may now log in.';

    header('Location: ../views/auth/login.php');
    exit;
}

$_SESSION['error'] = 'Registration failed. Please check your information and try again.';

header('Location: ../views/auth/register.php');
exit;