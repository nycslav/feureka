<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// 1. Handle Form Submissions (Delete Staff Account)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $targetUserId = filter_input(INPUT_POST, 'target_user_id', FILTER_VALIDATE_INT);

    if ($action === 'delete_staff' && $targetUserId) {
        // Use Lady's specific function to delete staff[cite: 3]
        if (deleteStaffAccount($targetUserId)) {
            $_SESSION['success_message'] = 'Staff account has been successfully removed.';
        } else {
            $_SESSION['error_message'] = 'Failed to remove account. Ensure this is a staff user.';
        }
        
        // Refresh to prevent double submission
        header('Location: user-management.php');
        exit;
    }
}

// 2. Fetch all registered users[cite: 3]
$users = getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - FEUreka Admin</title>
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

    <div class="admin-layout">
        
        <!-- THE UNIFORM SIDEBAR -->
        <?php 
            // Pure PHP way to get the current file name (e.g., "user-management.php")
            $currentPage = basename($_SERVER['PHP_SELF']); 
        ?>
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>FEUreka Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="pending-found-items.php" class="<?= $currentPage === 'pending-found-items.php' ? 'active' : '' ?>">Pending Found Items</a></li>
                    <li><a href="approved-found-items.php" class="<?= $currentPage === 'approved-found-items.php' ? 'active' : '' ?>">Approved Found Items</a></li>
                    <li><a href="missing-item-reports.php" class="<?= $currentPage === 'missing-item-reports.php' ? 'active' : '' ?>">Missing Item Reports</a></li>
                    <li><a href="archive-records.php" class="<?= $currentPage === 'archive-records.php' ? 'active' : '' ?>">Archive Records</a></li>
                    <li><a href="user-management.php" class="<?= $currentPage === 'user-management.php' ? 'active' : '' ?>">User Management</a></li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            
            <a href="dashboard.php" class="admin-back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>

            <div class="admin-header">
                <h1>User Management</h1>
                <p>View registered accounts and manage staff access across the system.</p>
            </div>

            <!-- SUCCESS/ERROR MESSAGES -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- THE UNIFORM TABLE -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Contact / Email</th>
                            <th>Account Role</th>
                            <th>Year Level</th>
                            <th>Registered On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #8BD2A6;">No users found in the system.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                    </td>
                                    <td>
                                        <span style="color: #8BD2A6;"><?= htmlspecialchars($user['email']) ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                            // Style badges based on role and user type
                                            if ($user['role'] === ROLE_ADMIN) {
                                                echo '<span class="badge bg-admin" style="background: rgba(77, 236, 247, 0.2); color: #4df7ec; border: 1px solid #4df7ec;">ADMINISTRATOR</span>';
                                            } elseif ($user['user_type'] === USER_TYPE_STAFF) {
                                                echo '<span class="badge bg-staff" style="background: rgba(224, 247, 77, 0.2); color: #f7e34d; border: 1px solid #f7e34d;">STAFF</span>';
                                            } else {
                                                echo '<span class="badge bg-student" style="background: rgba(100, 247, 77, 0.2); color: #4df778; border: 1px solid #4df778;">STUDENT</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($user['year_level'] ? $user['year_level'] : 'N/A') ?>
                                    </td>
                                    <td>
                                        <small style="color: #C6D1CC;">
                                            <?= htmlspecialchars(date('M d, Y', strtotime($user['created_at']))) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($user['user_type'] === USER_TYPE_STAFF && $user['role'] !== ROLE_ADMIN): ?>
                                            <!-- Inline HTML Form for deleting staff -->
                                            <form action="user-management.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="target_user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                                                <input type="hidden" name="action" value="delete_staff">
                                                <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.85rem; background: rgba(255, 76, 76, 0.1); color: #ff4c4c; border: 1px solid #ff4c4c;">
                                                    Remove
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #555; font-size: 0.85rem;">No actions</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>