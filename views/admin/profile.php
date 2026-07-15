<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Administrator Profile Page
 * ============================================================================
 *
 * Displays the authenticated administrator's profile information and allows
 * updating basic account details.
 * ============================================================================
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

requireAdmin();

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT
        first_name,
        last_name,
        email
     FROM users
     WHERE user_id = ?
     LIMIT 1"
);

if (!$stmt) {
    exit('Unable to load administrator profile.');
}

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    exit('Administrator account not found.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Profile | FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <h1>Administrator Profile</h1>

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

    <form
        action="../../actions/update-profile.php"
        method="POST"
    >

        <div>

            <label for="first_name">
                First Name
            </label>

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

            <label for="last_name">
                Last Name
            </label>

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

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($user['email']) ?>"
                required
            >

        </div>

        <br>

        <button type="submit">
            Save Changes
        </button>

    </form>

</body>

</html>