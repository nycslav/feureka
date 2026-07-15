<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/admin-navbar.php';
requireAdmin();

// 1. Fetch both found items and missing reports from the backend[cite: 3]
$archivedFound = getArchivedFoundItems();
$archivedMissing = getArchivedMissingReports();

// 2. Merge them into a single array using pure PHP
$archivedRecords = array_merge($archivedFound, $archivedMissing);

// 3. Sort them so the most recently archived items appear at the top
usort($archivedRecords, function($a, $b) {
    $timeA = strtotime($a['updated_at'] ?? '0');
    $timeB = strtotime($b['updated_at'] ?? '0');
    return $timeB <=> $timeA;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Records - FEUreka Admin</title>
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
        <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
        <!-- MAIN CONTENT -->
        <main class="admin-content">
            
            <a href="dashboard.php" class="admin-back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>

            <div class="admin-header">
                <h1>Archive Records</h1>
                <p>A read-only view of all resolved, claimed, and rejected items across the system.</p>
            </div>

            <!-- THE UNIFORM TABLE -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Archived Date</th>
                            <th>Status</th>
                            <th>Admin Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($archivedRecords)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #8BD2A6;">No archived records found in the system.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($archivedRecords as $record): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($record['item_name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($record['category_name']) ?></td>
                                    <td>
                                        <span style="color: #8BD2A6;">
                                            <?= htmlspecialchars(date('M d, Y', strtotime($record['updated_at']))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars(strtoupper(str_replace('_', ' ', $record['status']))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small style="color: #C6D1CC;">
                                            <?= htmlspecialchars($record['admin_notes'] ?? 'No notes provided.') ?>
                                        </small>
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