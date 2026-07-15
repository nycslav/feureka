<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/admin-navbar.php';
requireAdmin();

// 1. Handle Form Submissions (Accept or Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $adminId = $_SESSION['user_id'];

    if ($itemId && $action) {
        if ($action === 'accept') {
            // Using the specific approve function from functions.php
            if (approveItem($itemId, $adminId)) {
                $_SESSION['success_message'] = 'Item successfully approved and published.';
            } else {
                $_SESSION['error_message'] = 'Failed to approve item.';
            }
        } elseif ($action === 'reject') {
            // Using the general update function to mark it rejected
            if (updateItemStatus('found_items', $itemId, STATUS_REJECTED, $adminId)) {
                $_SESSION['success_message'] = 'Item report has been rejected.';
            } else {
                $_SESSION['error_message'] = 'Failed to reject item.';
            }
        }
        
        // Refresh the page to prevent duplicate form submissions
        header('Location: pending-found-items.php');
        exit;
    }
}

// 2. Fetch pending items
$pendingItems = getPendingItems(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Found Items - FEUreka Admin</title>
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
                <h1>Pending Found Items</h1>
                <p>Review items turned in by students. Approve them to display publicly or reject invalid reports.</p>
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
                        <?php if (empty($pendingItems)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #8BD2A6;">You're all caught up! No pending items to review.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendingItems as $item): ?>
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
                                        <span class="badge bg-warning">NEEDS REVIEW</span>
                                    </td>
                                    <td>
                                        <!-- INLINE ACTION BUTTONS -->
                                        <div style="display: flex; gap: 10px;">
                                            <!-- Accept Form -->
                                            <form action="pending-found-items.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="item_id" value="<?= htmlspecialchars((string)$item['item_id']) ?>">
                                                <input type="hidden" name="action" value="accept">
                                                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; background-color: #90EE90;">
                                                    Accept
                                                </button>
                                            </form>

                                            <!-- Reject Form -->
                                            <form action="pending-found-items.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="item_id" value="<?= htmlspecialchars((string)$item['item_id']) ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.85rem; background: rgba(255, 76, 76, 0.1); color: #ff4c4c; border: 1px solid #ff4c4c;">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
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