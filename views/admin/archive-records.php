<?php
// views/admin/archive-records.php

// 1. Standard Wiring
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 2. The Bouncer
//requireAdmin();

// 3. Fetch the Archived Data
$archived_items = getArchivedFoundItems(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Records - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Archived Records</h1>
                <p>Historical log of items that have been removed from the active public feed.</p>
            </header>

            <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #2c2c2c; color: white; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background-color: #1a1a1a; text-align: left;">
                        <th style="padding: 15px; border-bottom: 2px solid #555;">Item Name</th>
                        <th style="padding: 15px; border-bottom: 2px solid #555;">Location Found</th>
                        <th style="padding: 15px; border-bottom: 2px solid #555;">Date Found</th>
                        <th style="padding: 15px; border-bottom: 2px solid #555;">Processed By</th>
                        <th style="padding: 15px; border-bottom: 2px solid #555;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (empty($archived_items)): ?>
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #aaa;">The archive is currently empty.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($archived_items as $item): ?>
                            <tr>
                                <td style="padding: 15px; border-bottom: 1px solid #444;"><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td style="padding: 15px; border-bottom: 1px solid #444;"><?php echo htmlspecialchars($item['location_description']); ?></td>
                                <td style="padding: 15px; border-bottom: 1px solid #444;"><?php echo htmlspecialchars($item['date_found']); ?></td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #444;">
                                    <?php 
                                        if ($item['processed_by_first_name']) {
                                            echo htmlspecialchars($item['processed_by_first_name'] . ' ' . $item['processed_by_last_name']); 
                                        } else {
                                            echo "System";
                                        }
                                    ?>
                                </td>
                                
                                <td style="padding: 15px; border-bottom: 1px solid #444;">
                                    <span style="background-color: #555; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; text-transform: uppercase;">
                                        Archived
                                    </span>
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