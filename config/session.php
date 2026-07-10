<?php declare(strict_types=1);

/**
 * Reusable FEureka session utilities.
 *
 * This file does not implement login. It only reads the documented session
 * variables written by the authentication module.
 */

require_once __DIR__ . '/constants.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Regenerate the active session ID after successful authentication.
function regenerateSessionId(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_regenerate_id(true);
}


// Determine whether a user session is currently authenticated.
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '';
}

// Determine whether the current authenticated user is an administrator.
function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['role'] ?? null) === ROLE_ADMIN;
}

// Require an authenticated session before continuing.
function requireLogin(): void
{
    if (isLoggedIn()) {
        return;
    }

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(401);
    }

    exit('Authentication required.');
}

// Require an administrator session before continuing.
function requireAdmin(): void
{
    requireLogin();

    if (isAdmin()) {
        return;
    }

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(403);
    }

    exit('Administrator access required.');
}

// Clear the current user session and remove the session cookie.
function logoutUser(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $cookieParams = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $cookieParams['path'],
            $cookieParams['domain'],
            (bool) $cookieParams['secure'],
            (bool) $cookieParams['httponly']
        );
    }

    session_destroy();
}
