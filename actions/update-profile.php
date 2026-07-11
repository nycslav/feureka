<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Update Profile Action
 * ============================================================================
 *
 * Updates the authenticated user's basic profile information.
 * ============================================================================
 */

require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    if (isAdmin()) {
        header('Location: ../views/admin/profile.php');
    } else {
        header('Location: ../views/user/profile.php');
    }

    exit;
}

$userId = (int) $_SESSION['user_id'];

$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');

if (
    $firstName === ''
    || $lastName === ''
    || $email === ''
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    $_SESSION['error'] = 'Please provide valid profile information.';

    if (isAdmin()) {
        header('Location: ../views/admin/profile.php');
    } else {
        header('Location: ../views/user/profile.php');
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| Check if another user already uses the email
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT user_id
     FROM users
     WHERE email = ?
       AND user_id <> ?
     LIMIT 1"
);

if (!$stmt) {

    $_SESSION['error'] = 'Unable to process your request.';

    if (isAdmin()) {
        header('Location: ../views/admin/profile.php');
    } else {
        header('Location: ../views/user/profile.php');
    }

    exit;
}

$stmt->bind_param("si", $email, $userId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $stmt->close();

    $_SESSION['error'] = 'Email address is already in use.';

    if (isAdmin()) {
        header('Location: ../views/admin/profile.php');
    } else {
        header('Location: ../views/user/profile.php');
    }

    exit;
}

$stmt->close();

/*
|--------------------------------------------------------------------------
| Update Profile
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "UPDATE users
     SET first_name = ?,
         last_name = ?,
         email = ?
     WHERE user_id = ?"
);

if (!$stmt) {

    $_SESSION['error'] = 'Unable to process your request.';

    if (isAdmin()) {
        header('Location: ../views/admin/profile.php');
    } else {
        header('Location: ../views/user/profile.php');
    }

    exit;
}

$stmt->bind_param(
    "sssi",
    $firstName,
    $lastName,
    $email,
    $userId
);

if ($stmt->execute()) {

    $_SESSION['first_name'] = $firstName;
    $_SESSION['last_name'] = $lastName;
    $_SESSION['email'] = $email;

    $_SESSION['success'] = 'Profile updated successfully.';

} else {

    $_SESSION['error'] = 'Unable to update your profile.';
}

$stmt->close();

if (isAdmin()) {
    header('Location: ../views/admin/profile.php');
} else {
    header('Location: ../views/user/profile.php');
}

exit;