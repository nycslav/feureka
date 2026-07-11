<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka User Profile Page
 * ============================================================================
 *
 * Displays the authenticated user's profile information and allows updating
 * basic account details.
 * ============================================================================
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../config/constants.php';

requireLogin();

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT
        first_name,
        last_name,
        email,
        role,
        user_type,
        year_level,
        expiration_date
     FROM users
     WHERE user_id = ?
     LIMIT 1"
);

if (!$stmt) {
    exit('Unable to load profile.');
}

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    exit('User not found.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | FEUreka</title>
</head>

<body>

<h1>My Profile</h1>

<?php if (isset($_SESSION['success'])): ?>

    <p>
        <?= htmlspecialchars($_SESSION['success']) ?>
    </p>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>

    <p>
        <?= htmlspecialchars($_SESSION['error']) ?>
    </p>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

<form action="../../actions/update-profile.php" method="POST">

    <div>

        <label for="first_name">First Name</label>

        <input
            type="text"
            id="first_name"
            name="first_name"
            value="<?= htmlspecialchars($user['first_name']) ?>"
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
            value="<?= htmlspecialchars($user['last_name']) ?>"
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
            value="<?= htmlspecialchars($user['email']) ?>"
            required
        >

    </div>

    <br>

    <div>

        <label>User Type</label>

        <input
            type="text"
            value="<?= htmlspecialchars((string) $user['user_type']) ?>"
            readonly
        >

    </div>

    <?php if ($user['user_type'] === USER_TYPE_STUDENT): ?>

        <br>

        <div>

            <label>Year Level</label>

            <input
                type="text"
                value="<?= htmlspecialchars((string) $user['year_level']) ?>"
                readonly
            >

        </div>

        <br>

        <div>

            <label>Account Expiration</label>

            <input
                type="date"
                value="<?= htmlspecialchars((string) $user['expiration_date']) ?>"
                readonly
            >

        </div>

    <?php endif; ?>

    <br>

    <p>
        <strong>Note:</strong>
        User Type<?php if ($user['user_type'] === USER_TYPE_STUDENT): ?>, Year Level, and Account Expiration<?php endif; ?>
        are managed by the system and cannot be modified from this page.
        If corrections are needed, please contact the system administrator.
    </p>

    <br>

    <button type="submit">
        Save Changes
    </button>

</form>

</body>

</html>