<?php
// actions/archive-item.php

// 1. Standard Wiring
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';

// 2. The Bouncer
requireAdmin();

// 3. Process the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and validate incoming data
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $table = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // If no table is provided, default to found_items
    if (!$table) {
        $table = 'found_items';
    }

    // Ensure valid inputs before hitting the database
    if ($itemId && ($table === 'found_items' || $table === 'missing_reports')) {
        
        // Execute Lady's built-in archive function
        $success = archiveItem($table, $itemId);

        if ($success) {
            $_SESSION['success_msg'] = "Item successfully moved to the archive.";
        } else {
            $_SESSION['error_msg'] = "Failed to archive the item. It may have already been moved.";
        }
    } else {
        $_SESSION['error_msg'] = "Invalid data submitted.";
    }

    // 4. Smart Redirect
    // Send the admin back to the page they just came from
    if ($table === 'missing_reports') {
        header("Location: ../views/admin/missing-item-reports.php");
    } else {
        header("Location: ../views/admin/approved-found-items.php");
    }
    exit;
}

// 5. Fallback Redirect (If someone tries to type the URL manually without submitting a form)
header("Location: ../views/admin/dashboard.php");
exit;