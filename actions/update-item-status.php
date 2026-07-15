<?php
// actions/update-item-status.php

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';

<<<<<<< HEAD
// 2. Security Check: Instantly boot anyone who isn't a logged-in admin
=======
// 1. RESTORE AUTHORIZATION: Unleash the bouncer
>>>>>>> origin/feature/admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Securely capture incoming data
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $status  = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $notes   = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
    
    // 2. SUPPORT BOTH TABLES: Dynamically accept the table from the form
    $table = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING); 
    
    // Safety fallback: Ensure it only ever hits these two specific tables
    if (!$table || !in_array($table, ['found_items', 'missing_reports'])) {
        $table = 'found_items';
    }

    $adminId = $_SESSION['user_id'];
    
    if ($item_id && $status) {
        
        // 3. MATCH PARAMETERS: Pass the dynamic $table instead of hardcoding 'found_items'
        // Signature: updateItemStatus($table, $itemId, $status, $adminId, $notes)
        $success = updateItemStatus($table, $item_id, $status, $adminId, $notes);
        
        // Hook into the new UI alert banners instead of URL GET parameters
        if ($success) {
            $cleanStatus = strtoupper(str_replace('_', ' ', $status));
            $_SESSION['success_message'] = "Item status successfully updated to {$cleanStatus}.";
        } else {
            $_SESSION['error_message'] = "Failed to update item status. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid data submitted.";
    }

    // 4. FIX ROUTING VARIABLES: Drop the undefined BASE_URL and use smart relative paths
    if ($table === 'missing_reports') {
        header("Location: ../views/admin/missing-item-reports.php");
    } else {
        header("Location: ../views/admin/approved-found-items.php");
    }
    exit;
    
} else {
    // Fallback for direct browser access (GET request)
    header("Location: ../views/admin/dashboard.php");
    exit;
}