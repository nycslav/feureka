<?php
// views/admin/user-management.php

// 1. Standard Wiring
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 2. The Bouncer
// requireAdmin(); // (Keep commented out for testing)

// 3. Fetch the users
$users = getAllUsers(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>User Management</h1>
                    <p>Manage staff accounts and monitor student data expiration.</p>
                </div>
                
                <form action="../../actions/manage-users.php" method="POST">
                    <input type="hidden" name="action" value="cleanup_expired">
                    <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        Clean Up Expired Students
                    </button>
                </form>
            </header>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; color: black; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background-color: #0a4622; color: white; text-align: left;">
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Name</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Email</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Role</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">User Type</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Expiration Date</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center;">No users found in the system.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($user['email']); ?></td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <strong><?php echo htmlspecialchars($user['role']); ?></strong>
                                </td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <?php echo htmlspecialchars($user['user_type'] ?? 'N/A'); ?>
                                </td>

                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <?php echo htmlspecialchars($user['expiration_date'] ?? 'Permanent'); ?>
                                </td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <?php if ($user['role'] === ROLE_ADMIN): ?>
                                        <span style="color: gray; font-style: italic;">Protected</span>
                                    <?php elseif ($user['user_type'] === USER_TYPE_STAFF): ?>
                                        <form action="../../actions/manage-users.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_staff">
                                            <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                                            <button type="submit" style="background-color: #d32f2f; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Delete Staff</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: gray; font-size: 0.9em;">Auto-expires</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </main>
    </div>
</body>
</html>