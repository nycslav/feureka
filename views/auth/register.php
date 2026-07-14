<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Registration Page
 * ============================================================================
 *
 * Displays the registration form for new FEUreka users.
 * Registration requests are processed by:
 * actions/register-actions.php
 * ============================================================================
 */

require_once '../../config/session.php';
require_once '../../config/constants.php';

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

$pageTitle = 'Register | FEUreka';

require_once '../../includes/header.php';
?>

<div class="auth-page">

    <div class="auth-card">

        <div class="auth-brand">

            <img
                src="../../assets/images/logo.png"
                alt="FEUreka Logo"
                class="auth-logo">

            <h1>Create Account</h1>

            <p>
                Join FEUreka and start reporting and recovering lost items.
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
            action="../../actions/register-actions.php"
            method="POST">

            <div class="form-group">

                <label for="first_name">
                    First Name
                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        person
                    </span>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder="Enter your first name"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label for="last_name">
                    Last Name
                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        badge
                    </span>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder="Enter your last name"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        mail
                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your FEU Tech email"
                        required>

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
                        placeholder="Create a password"
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

            <div class="form-group">

                <label for="user_type">
                    User Type
                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        school
                    </span>

                    <select
                        id="user_type"
                        name="user_type"
                        required>

                        <option value="">-- Select User Type --</option>

                        <option value="<?= USER_TYPE_STUDENT ?>">
                            Student
                        </option>

                        <option value="<?= USER_TYPE_STAFF ?>">
                            Staff
                        </option>

                    </select>

                     <span class="material-symbols-outlined select-arrow">
                        expand_more
                    </span>

                </div>

            </div>

            <div
                class="form-group"
                id="year-level-container"
                hidden>

                <label for="year_level">
                    Year Level
                </label>

                <div class="input-wrapper">

                    <span class="material-symbols-outlined input-icon">
                        calendar_today
                    </span>

                    <select
                        id="year_level"
                        name="year_level">

                        <option value="">-- Select Year Level --</option>

                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>

                    </select>

                     <span class="material-symbols-outlined select-arrow">
                        expand_more
                    </span>

                </div>

            </div>

            <button
                class="auth-button"
                type="submit">

                Create Account

            </button>

        </form>

        <p class="auth-link">

            Already have an account?

            <a href="login.php">

                Login here

            </a>

        </p>

    </div>

</div>

<script>

    const userType = document.getElementById('user_type');
    const yearLevelContainer = document.getElementById('year-level-container');
    const yearLevel = document.getElementById('year_level');

    userType.addEventListener('change', function () {

        if (this.value === '<?= USER_TYPE_STUDENT ?>') {

            yearLevelContainer.hidden = false;
            yearLevel.required = true;

        } else {

            yearLevelContainer.hidden = true;
            yearLevel.required = false;
            yearLevel.value = '';

        }

    });


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

</body>
</html>