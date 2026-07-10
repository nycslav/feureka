<?php
// views/admin/missing-item-reports.php

// 1. Standard Wiring
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 2. The Bouncer
// requireAdmin(); // (Keep this commented out until we build the login system)

// 3. Fetch the data using Lady's new function
$missing_reports = getMissingReports(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Item Reports - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Missing Item Reports</h1>
                <p>Review items that students have reported as lost. Update their status if a match is found.</p>
            </header>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; color: black; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background-color: #ffc107; text-align: left;">
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Lost Item Name</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Last Seen Location</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Date Lost</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Current Status</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ccc;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (empty($missing_reports)): ?>
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center;">No missing item reports at this time.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($missing_reports as $report): ?>
                            <tr>
                                <td style="padding: 15px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($report['item_name']); ?></td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($report['location_description']); ?></td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($report['date_lost']); ?></td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    <strong><?php echo htmlspecialchars($report['status']); ?></strong>
                                </td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                    
                                    <form action="../../actions/update-item-status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="table" value="missing_reports">
                                        <input type="hidden" name="item_id" value="<?php echo $report['report_id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo STATUS_POSSIBLE_MATCH; ?>">
                                        <button type="submit" style="background-color: #03a9f4; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Flag as Match</button>
                                    </form>

                                    <form action="../../actions/update-item-status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="table" value="missing_reports">
                                        <input type="hidden" name="item_id" value="<?php echo $report['report_id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo STATUS_RESOLVED; ?>">
                                        <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Resolve</button>
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