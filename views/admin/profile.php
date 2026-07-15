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

$fullName = trim($user['first_name'] . ' ' . $user['last_name']);

$initials = strtoupper(
    mb_substr($user['first_name'], 0, 1) . mb_substr($user['last_name'], 0, 1)
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Profile | FEUreka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/user.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

    <?php require_once __DIR__ . '/../../includes/admin-navbar.php'; ?>

    <div class="admin-layout">

        <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">

            <a href="dashboard.php" class="admin-back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>

            <div class="admin-header">
                <h1>Administrator Profile</h1>
                <p>Manage your administrator account information.</p>
            </div>

            <section class="report-section">

                <div class="report-card">

                    <div class="profile-header">

                        <div class="profile-avatar">

                            <?= htmlspecialchars($initials) ?>

                        </div>

                        <div class="profile-header-info">

                            <h2 class="profile-name">

                                <?= htmlspecialchars($fullName) ?>

                            </h2>

                            <p class="profile-email">

                                <span class="material-symbols-outlined">mail</span>

                                <?= htmlspecialchars($user['email']) ?>

                            </p>

                            <span class="profile-badge">

                                <span class="material-symbols-outlined">admin_panel_settings</span>

                                Administrator

                            </span>

                        </div>

                    </div>

                    <?php if (isset($_SESSION['success'])): ?>

                        <div class="auth-message success">

                            <?= htmlspecialchars($_SESSION['success']) ?>

                        </div>

                        <?php unset($_SESSION['success']); ?>

                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>

                        <div class="auth-message error">

                            <?= htmlspecialchars($_SESSION['error']) ?>

                        </div>

                        <?php unset($_SESSION['error']); ?>

                    <?php endif; ?>

                    <form
                        class="auth-form"
                        action="../../actions/update-profile.php"
                        method="POST">

                        <div class="report-group">

                            <h2 class="report-group-title">

                                Personal Information

                            </h2>

                            <div class="report-grid">

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
                                            value="<?= htmlspecialchars($user['first_name']) ?>"
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
                                            value="<?= htmlspecialchars($user['last_name']) ?>"
                                            required>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group">

                                <label for="email">
                                    Email Address
                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">
                                        mail
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="<?= htmlspecialchars($user['email']) ?>"
                                        required>

                                </div>

                            </div>

                        </div>

                        <div class="report-group">

                            <h2 class="report-group-title">

                                Account Information

                            </h2>

                            <div class="form-group">

                                <label>
                                    Account Role
                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">
                                        admin_panel_settings
                                    </span>

                                    <input
                                        type="text"
                                        value="Administrator"
                                        readonly>

                                    <span class="material-symbols-outlined lock-icon">
                                        lock
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div class="report-actions profile-actions">

                            <a href="dashboard.php" class="profile-cancel-link">
                                Cancel
                            </a>

                            <button
                                class="auth-button"
                                type="submit">

                                <span class="material-symbols-outlined">save</span>

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </main>

    </div>

    <script src="../../assets/js/sidebar.js"></script>

</body>

</html>
