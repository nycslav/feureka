<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Fetch approved items using the function from functions.php
$approvedItems = getApprovedItems(); 
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
                <h1>Approved Found Items</h1>
                <p>View and manage found items that are currently published and visible to the public.</p>
            </div>

            <!-- SUCCESS/ERROR MESSAGES -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- THE READABLE TABLE -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th>Category</th>
                            <th>Location Found</th>
                            <th>Finder</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($approvedItems)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #8BD2A6;">There are currently no approved found items in the system.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($approvedItems as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                        <br>
                                        <small style="color: #8BD2A6;"><?= htmlspecialchars(date('M d, Y', strtotime($item['date_found']))) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($item['category_name']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($item['room'] . ' / ' . $item['floor']) ?>
                                    </td>
                                    <td>
                                        <?php 
                                            // Safely fetch reporter names using the correct database keys
                                            $firstName = $item['reporter_first_name'] ?? 'Unknown';
                                            $lastName = $item['reporter_last_name'] ?? '';
                                            echo htmlspecialchars(trim($firstName . ' ' . $lastName));
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">APPROVED</span>
                                    </td>
                                    <td>
                                        <!-- Link to the uniform update tool to mark as claimed/archived -->
                                        <a href="update-item-status.php?table=found_items&id=<?= htmlspecialchars((string)$item['item_id']) ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem;">
                                            Update Status
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