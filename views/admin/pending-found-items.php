<?php
// views/admin/pending-found-items.php

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 1. Check security
//requireAdmin();

// 2. Get the data from the database (Using Lady's function)
// Right now this returns an empty array [], but it will work automatically when she finishes her code.
$pending_items = getPendingItems(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Found Items - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Pending Found Items</h1>
                <p>Review items submitted by users. Approve them to display them on the public feed.</p>
            </header>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #f4f4f4; text-align: left;">
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Item Name</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Location Found</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Date Reported</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (empty($pending_items)): ?>
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center;">No pending items at the moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_items as $item): ?>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['location_description']); ?></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['date_found']); ?></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    
                                    <form action="../../actions/update-item-status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo STATUS_APPROVED; ?>">
                                        <button type="submit" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;">Approve</button>
                                    </form>

                                    <form action="../../actions/update-item-status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo STATUS_REJECTED; ?>">
                                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Reject</button>
                                    </form>

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