<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Fetch missing item reports using the function from functions.php
$missingReports = getMissingReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Item Reports - FEUreka Admin</title>
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
                <h1>Missing Item Reports</h1>
                <p>Review and manage items reported lost by students and staff.</p>
            </div>

            <!-- SUCCESS/ERROR MESSAGES -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- THE UNIFORM TABLE -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Reporter</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($missingReports)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #8BD2A6;">No active missing item reports found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($missingReports as $report): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($report['item_name']) ?></strong>
                                        <br><small style="color: #8BD2A6;"><?= htmlspecialchars(date('M d, Y', strtotime($report['date_lost']))) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($report['category_name']) ?></td>
                                    <td><?= htmlspecialchars($report['room'] . ' / ' . $report['floor']) ?></td>
                                    <td>
                                        <?= htmlspecialchars(trim(($report['reporter_first_name'] ?? 'Unknown') . ' ' . ($report['reporter_last_name'] ?? ''))) ?><br>
                                        <small><?= htmlspecialchars($report['contact_number'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        // Assign the custom badge classes based on status
                                        $badgeClass = 'bg-secondary';
                                        
                                        if ($report['status'] === STATUS_OPEN) {
                                            $badgeClass = 'bg-warning'; // Gold
                                        } elseif ($report['status'] === STATUS_POSSIBLE_MATCH) {
                                            $badgeClass = 'bg-danger'; // RED - Draws immediate attention
                                        } elseif ($report['status'] === STATUS_RESOLVED) {
                                            $badgeClass = 'bg-success'; // Green
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars(strtoupper(str_replace('_', ' ', $report['status']))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Pure HTML link to the update page -->
                                        <a href="update-item-status.php?table=missing_reports&id=<?= htmlspecialchars((string)$report['report_id']) ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem;">
                                            Update
                                        </a>
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