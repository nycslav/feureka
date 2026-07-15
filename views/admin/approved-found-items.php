<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/admin-navbar.php';
requireAdmin();

// 1. Fetch the different active statuses using Lady's existing functions[cite: 1]
$approved = getApprovedItems(); 

// 2. Use the internal helper function to grab the other states WITHOUT editing functions.php[cite: 1]
$underReview = feurekaFoundItemsByStatus(STATUS_UNDER_REVIEW);
$claimed = feurekaFoundItemsByStatus(STATUS_CLAIMED);

// 3. Merge them into a single active pipeline array
$activeItems = array_merge($approved, $underReview, $claimed);

// 4. Sort by the most recently updated (or created) so fresh changes stay at the top
usort($activeItems, function($a, $b) {
    $timeA = strtotime($a['updated_at'] ?? $a['created_at']);
    $timeB = strtotime($b['updated_at'] ?? $b['created_at']);
    return $timeB <=> $timeA;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Found Items - FEUreka Admin</title>
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

    <div class="admin-layout">
        
        <!-- THE UNIFORM SIDEBAR -->
        <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
        <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
        <!-- MAIN CONTENT -->
        <main class="admin-content">
            
            <a href="dashboard.php" class="admin-back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>

            <div class="admin-header">
                <h1>Approved Found Items</h1>
                <p>View and manage approved found items that are currenlty pusblished and visible to the public.</p>
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

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Current Status</th>
                            <th>Manage Workflow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($activeItems)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px; color: #8BD2A6;">No active items in the pipeline.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($activeItems as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($item['category_name']) ?>
                                    </td>
                                    <td>
                                        <!-- Dynamic Badge Color Based on Status -->
                                        <?php 
                                            $badgeColor = '#F7C54D'; // Default gold
                                            $displayText = 'POSTED'; // UI Override for 'Approved'
                                            
                                            if ($item['status'] === STATUS_UNDER_REVIEW) {
                                                $badgeColor = '#3498db'; 
                                                $displayText = 'UNDER REVIEW';
                                            }
                                            if ($item['status'] === STATUS_CLAIMED) {
                                                $badgeColor = '#8BD2A6'; 
                                                $displayText = 'CLAIMED';
                                            }
                                        ?>
                                        <span class="badge" style="background: rgba(0,0,0,0.2); border: 1px solid <?= $badgeColor ?>; color: <?= $badgeColor ?>; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;">
                                            <?= htmlspecialchars($displayText) ?>
                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 10px; align-items: center;">
                                        
                                        <!-- Status Update Form -->
                                        <form action="../../actions/update-item-status.php" method="POST" style="margin: 0; display: flex; gap: 5px;">
                                            <input type="hidden" name="item_id" value="<?= htmlspecialchars((string)$item['item_id']) ?>">
                                            <input type="hidden" name="table" value="found_items">
                                            <select name="status" style="padding: 6px; border-radius: 5px; background: #001B11; color: #C6D1CC; border: 1px solid rgba(247, 197, 77, 0.2);">
                                                <option value="<?= STATUS_APPROVED ?>" <?= $item['status'] === STATUS_APPROVED ? 'selected' : '' ?>>Posted</option>
                                                <option value="<?= STATUS_UNDER_REVIEW ?>" <?= $item['status'] === STATUS_UNDER_REVIEW ? 'selected' : '' ?>>Under Review</option>
                                                <option value="<?= STATUS_CLAIMED ?>" <?= $item['status'] === STATUS_CLAIMED ? 'selected' : '' ?>>Claimed</option>
                                            </select>
                                            <button type="submit" class="btn" style="padding: 6px 12px;">Update</button>
                                        </form>

                                        <!-- Archive Button Form (Only appears if Claimed) -->
                                        <?php if ($item['status'] === STATUS_CLAIMED): ?>
                                            <form action="../../actions/archive-item.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="item_id" value="<?= htmlspecialchars((string)$item['item_id']) ?>">
                                                <input type="hidden" name="table" value="found_items">
                                                <button type="submit" class="btn" style="padding: 6px 12px; background: rgba(247, 162, 77, 0.68); border-color: #F7C54D;">Archive</button>
                                            </form>
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
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>