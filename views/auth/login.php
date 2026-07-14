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

    $pageTitle = 'Login | FEUreka';

    require_once '../../includes/header.php';
?>

<div class="auth-page">

    <div class="auth-card">

        <div class="auth-brand">

            <img
                src="../../assets/images/logo.png"
                alt="FEUreka Logo"
                class="auth-logo">

            <h1>Welcome Back</h1>

            <p>
                Sign in to continue to FEUreka.
            </p>

        </div>

        <?php if (isset($_SESSION['error'])): ?>

            <div class="auth-message error">

                <?= htmlspecialchars($_SESSION['error']) ?>

            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>

            <div class="auth-message success">

                <?= htmlspecialchars($_SESSION['success']) ?>

            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <form
            class="auth-form"
            action="../../actions/login-actions.php"
            method="POST">

            <div class="form-group">

                <label for="email">

                    Email

                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">

                        person

                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your FEU Tech email"
                        required
                        autofocus>
                </div>

            </div>

            <div class="form-group">

                <label for="password">

                    Password

                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        lock
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required>

                    <button
                        class="password-toggle"
                        type="button"
                        aria-label="Toggle Password">

                        <span class="material-symbols-outlined">
                            visibility
                        </span>

                    </button>

                </div>

            </div>

            <button
                class="auth-button"
                type="submit">

                Login

            </button>

        </form>

        <p class="auth-link">

            Don't have an account?

            <a href="register.php">

                Register here

            </a>

        </p>

    </div>

</div>

</body>
</html>

<script>

document.querySelectorAll('.password-toggle').forEach(button => {

    button.addEventListener('click', () => {

        const input = button.parentElement.querySelector('input');

        const icon = button.querySelector('.material-symbols-outlined');

        if (input.type === 'password') {

            input.type = 'text';

            icon.textContent = 'visibility_off';

        } else {

            input.type = 'password';

            icon.textContent = 'visibility';

        }

    });

});

</script>