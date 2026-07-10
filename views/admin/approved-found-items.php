<?php
// views/admin/approved-found-items.php

// 1. Include the wiring
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 2. Secure the page
//requireAdmin();

// 3. Fetch the data using Lady's specific function for approved items
$approved_items = getApprovedItems(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Found Items - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Approved Found Items</h1>
                <p>These items have been verified and are currently visible to students on the public feed.</p>
            </header>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #e8f5e9; text-align: left;">
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Item Name</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Location Found</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Date Reported</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Status</th>
                        <th style="padding: 10px; border-bottom: 2px solid #ccc;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (empty($approved_items)): ?>
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center;">No approved items to display.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($approved_items as $item): ?>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['location_description']); ?></td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($item['date_found']); ?></td>
                                
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <span style="background-color: #4CAF50; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.9em;">
                                        <?php echo STATUS_APPROVED; ?>
                                    </span>
                                </td>
                                
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <form action="../../actions/update-item-status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo STATUS_ARCHIVED; ?>">
                                        <button type="submit" style="background-color: #607d8b; color: white; border: none; padding: 5px 10px; cursor: pointer;">Archive</button>
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