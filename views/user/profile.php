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

$fullName = trim($user['first_name'] . ' ' . $user['last_name']);

$initials = strtoupper(
    mb_substr($user['first_name'], 0, 1) . mb_substr($user['last_name'], 0, 1)
);

$roleLabel = ucwords(str_replace('_', ' ', (string) $user['user_type']));

$pageTitle = 'My Profile | FEUreka';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/user-sidebar.php';

?>
<main>

    <section class="report-section">

        <div class="container">

            <div class="report-header">

                <h1 class="page-title">

                    My Profile

                </h1>

                <p class="page-description">

                    Manage your personal account information and review your account details.

                </p>

            </div>

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

                            <span class="material-symbols-outlined">verified</span>

                            <?= htmlspecialchars($roleLabel) ?>

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

                    <!-- ====================================================== -->
                    <!-- PERSONAL INFORMATION -->
                    <!-- ====================================================== -->

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

                    <!-- ====================================================== -->
                    <!-- ACCOUNT INFORMATION -->
                    <!-- ====================================================== -->

                                        <div class="report-group">

                        <h2 class="report-group-title">

                            Account Information

                        </h2>

                        <div class="form-group">

                            <label>

                                User Type

                            </label>

                            <div class="input-wrapper">

                                <span class="material-symbols-outlined input-icon">

                                    school

                                </span>

                                <input
                                    type="text"
                                    value="<?= htmlspecialchars((string)$user['user_type']) ?>"
                                    readonly>

                                <span class="material-symbols-outlined lock-icon">

                                    lock

                                </span>

                            </div>

                        </div>

                        <?php if ($user['user_type'] === USER_TYPE_STUDENT): ?>

                            <div class="report-grid">

                                <div class="form-group">

                                    <label>

                                        Year Level

                                    </label>

                                    <div class="input-wrapper">

                                        <span class="material-symbols-outlined input-icon">

                                            workspace_premium

                                        </span>

                                        <input
                                            type="text"
                                            value="<?= htmlspecialchars((string)$user['year_level']) ?>"
                                            readonly>

                                        <span class="material-symbols-outlined lock-icon">

                                            lock

                                        </span>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label>

                                        Account Expiration

                                    </label>

                                    <div class="input-wrapper">

                                        <span class="material-symbols-outlined input-icon">

                                            event

                                        </span>

                                        <input
                                            type="date"
                                            value="<?= htmlspecialchars((string)$user['expiration_date']) ?>"
                                            readonly>

                                        <span class="material-symbols-outlined lock-icon">

                                            lock

                                        </span>

                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="auth-message info">

                        <span class="material-symbols-outlined">

                            info

                        </span>

                        <div>

                            <strong>System-managed Information</strong><br>

                            User Type<?php if ($user['user_type'] === USER_TYPE_STUDENT): ?>, Year Level and Account Expiration<?php endif; ?>
                            are maintained by the administrator and cannot be modified from this page.

                        </div>

                    </div>

                    <div class="report-actions profile-actions">

                        <a href="home.php" class="profile-cancel-link">

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

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>