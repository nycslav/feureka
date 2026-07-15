<?php declare(strict_types=1);

/**
 * FEUreka application entry point.
 *
 * This file performs only initial routing. Page rendering, authentication,
 * registration, logout, and business logic are handled by their own modules.
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/session.php';

if (!isLoggedIn()) {
    header('Location: views/auth/login.php');
    exit;
}

if (isAdmin()) {
    header('Location: views/admin/dashboard.php');
    exit;
}

header('Location: views/user/home.php');
exit;
