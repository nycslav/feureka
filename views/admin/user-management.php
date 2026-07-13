<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure only admins can access this view
requireAdmin();

$users = getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - FEUreka Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css"><!-- Add your CSS framework links here (e.g., Bootstrap or Tailwind) -->
</head>
<body>
    <!-- Replace with your actual admin sidebar/navbar inclusion -->
    <?php // include __DIR__ . '/../partials/admin_navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>User Management</h2>
        </div>

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

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Type</th>
                                <th>Academic Details</th>
                                <th>Date Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No users found in the system.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge <?= $user['role'] === ROLE_ADMIN ? 'bg-danger' : 'bg-primary' ?>">
                                                <?= htmlspecialchars(strtoupper($user['role'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= $user['user_type'] ? htmlspecialchars(ucfirst($user['user_type'])) : '<span class="text-muted">N/A</span>' ?>
                                        </td>
                                        <td>
                                            <?php if ($user['user_type'] === USER_TYPE_STUDENT): ?>
                                                <small>
                                                    <strong>Year:</strong> <?= htmlspecialchars((string) $user['year_level']) ?><br>
                                                    <strong>Exp:</strong> <?= htmlspecialchars((string) $user['expiration_date']) ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($user['created_at']))) ?></td>
                                        <td>
                                            <?php 
                                            // Security Check: Only render the delete form if the user is a STAFF member
                                            if ($user['role'] === ROLE_USER && $user['user_type'] === USER_TYPE_STAFF): 
                                            ?>
                                                <form action="../../actions/delete-staff.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this staff account? This action cannot be undone.');">
                                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars((string) $user['user_id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">No actions</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Replace with your actual footer inclusion -->
    <?php // include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>