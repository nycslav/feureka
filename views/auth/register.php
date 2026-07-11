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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FEUreka</title>
</head>

<body>

    <h1>FEUreka</h1>

    <h2>Create an Account</h2>

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

    <form action="../../actions/register-actions.php" method="POST">

        <div>

            <label for="first_name">First Name</label>

            <input
                type="text"
                id="first_name"
                name="first_name"
                required
            >

        </div>

        <br>

        <div>

            <label for="last_name">Last Name</label>

            <input
                type="text"
                id="last_name"
                name="last_name"
                required
            >

        </div>

        <br>

        <div>

            <label for="email">Email</label>

            <input
                type="email"
                id="email"
                name="email"
                required
            >

        </div>

        <br>

        <div>

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>

        <br>

        <div>

            <label for="user_type">User Type</label>

            <select
                id="user_type"
                name="user_type"
                required
            >
                <option value="">-- Select User Type --</option>
                <option value="<?= USER_TYPE_STUDENT ?>">
                    Student
                </option>
                <option value="<?= USER_TYPE_STAFF ?>">
                    Staff
                </option>
            </select>

        </div>

        <br>

        <div id="year-level-container" hidden>

            <label for="year_level">Year Level</label>

            <select
                id="year_level"
                name="year_level"
            >
                <option value="">-- Select Year Level --</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>

        </div>

        <br>

        <button type="submit">
            Register
        </button>

    </form>

    <p>

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </p>

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

    </script>

</body>

</html>