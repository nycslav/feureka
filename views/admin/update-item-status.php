<?php declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

// FIX: Initialize the error variable
$error = ''; 

requireAdmin();

// 1. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING);
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'admin_notes', FILTER_SANITIZE_STRING) ?: null;
    $adminId = $_SESSION['user_id'];

    if ($table && $recordId && $status) {
        if (updateItemStatus($table, (int)$recordId, $status, (int)$adminId, $notes)) {
            $_SESSION['success_message'] = 'Item status and notes successfully updated.';
            $redirect = $table === 'found_items' ? 'approved-found-items.php' : 'missing-item-reports.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Failed to update. Ensure you selected a valid status for this item type.';
        }
    } else {
        $error = 'Missing required fields.';
    }
}

// 2. Handle Page Load
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING) ?? filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING);
$recordId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);

if (!$table || !$recordId) {
    $_SESSION['error_message'] = 'No record was selected for update.';
    header('Location: dashboard.php');
    exit;
}

// 3. Layout includes
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/admin-navbar.php';
?>
<?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
<main class="admin-content">
    <head>
        <!-- ... your existing meta and font tags ... -->

        <!-- Global Styles -->
        <link rel="stylesheet" href="../../assets/css/style.css">
        <link rel="stylesheet" href="../../assets/css/user.css">
        
        <!-- ADD THIS LINE HERE -->
        <link rel="stylesheet" href="../../assets/css/admin.css">
        
        <!-- ... rest of your header ... -->
    </head> 
    
    <a href="missing-item-reports.php" class="admin-back-btn">
        <span class="material-symbols-outlined">arrow_back</span> Back to Missing Reports
    </a>

    <div style="max-width: 600px; margin: 0 auto;">
        
        <div style="background: #1A2820; border-radius: 30px; padding: 40px; border: 1px solid rgba(247, 197, 77, 0.2); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);">
            
            <h1 style="color: #F7C54D; margin-bottom: 30px; text-align: center; font-size: 1.8rem;">Update Record Status</h1>

            <?php if (!empty($error)): ?>
                <div style="background: rgba(255, 87, 87, 0.1); border: 1px solid #ff5757; color: #ff5757; padding: 15px; border-radius: 15px; margin-bottom: 25px; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="update-item-status.php" method="POST">
                <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
                <input type="hidden" name="record_id" value="<?= htmlspecialchars((string)$recordId) ?>">

                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 12px; color: #C6D1CC; font-weight: 600;">New Status</label>
                    <select name="status" required style="width: 100%; padding: 16px; background: #08110D; border: 1px solid rgba(247, 197, 77, 0.3); color: #FFFFFF; border-radius: 15px; font-size: 1rem; cursor: pointer;">
                        <option value="" disabled selected>Select a status...</option>
                        <?php if ($table === 'found_items'): ?>
                            <option value="<?= STATUS_PENDING ?>">Pending</option>
                            <option value="<?= STATUS_APPROVED ?>">Approved</option>
                            <option value="<?= STATUS_UNDER_REVIEW ?>">Under Review</option>
                            <option value="<?= STATUS_CLAIMED ?>">Claimed</option>
                            <option value="<?= STATUS_REJECTED ?>">Rejected</option>
                            <option value="<?= STATUS_ARCHIVED ?>">Archived</option>
                        <?php elseif ($table === 'missing_reports'): ?>
                            <option value="<?= STATUS_OPEN ?>">Open</option>
                            <option value="<?= STATUS_POSSIBLE_MATCH ?>">Possible Match</option>
                            <option value="<?= STATUS_RESOLVED ?>">Resolved</option>
                            <option value="<?= STATUS_ARCHIVED ?>">Archived</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 12px; color: #C6D1CC; font-weight: 600;">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="4" placeholder="Enter details about this status change..." style="width: 100%; padding: 16px; background: #08110D; border: 1px solid rgba(247, 197, 77, 0.3); color: #FFFFFF; border-radius: 15px; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 15px;">
                    <a href="missing-item-reports.php" style="flex: 1; text-align: center; padding: 16px; text-decoration: none; border: 1px solid #8BD2A6; color: #8BD2A6; border-radius: 999px; font-weight: 600; transition: .2s;">Cancel</a>
                    <button type="submit" style="flex: 1; padding: 16px; background: #F7C54D; color: #001B11; border: none; border-radius: 999px; font-weight: 700; cursor: pointer; transition: .2s;">Save Updates</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>