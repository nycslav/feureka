<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Login Page
 * ============================================================================
 *
 * Displays the login form for registered users and administrators.
 * Authentication requests are processed by:
 * actions/login-actions.php
 * ============================================================================
 */

require_once '../../config/session.php';

/*
|--------------------------------------------------------------------------
| Redirect authenticated users
|--------------------------------------------------------------------------
*/

if (isLoggedIn()) {

    if (isAdmin()) {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: ../user/home.php');
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FEUreka</title>
</head>

<body>

    <h1>FEUreka</h1>

    <h2>Login</h2>

    <?php if (isset($_SESSION['error'])): ?>

        <p>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </p>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>

        <p>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </p>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <form action="../../actions/login-actions.php" method="POST">

        <div>

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                required
                autofocus
            >

        </div>

        <br>

        <div>

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>

        <br>

        <button type="submit">
            Login
        </button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</body>

</html>