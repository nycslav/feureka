<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Logout Action
 * ============================================================================
 *
 * Ends the current authenticated session and redirects the user
 * to the login page.
 * ============================================================================
 */

require_once '../config/session.php';

logoutUser();

header('Location: ../views/auth/login.php');
exit;