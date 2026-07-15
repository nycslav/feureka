<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Shared HTML Header
 * ---------------------------------------------------------
 * Shared <head> section for UI pages.
 * Individual pages may override the browser title by
 * setting $pageTitle before requiring this file.
 * ---------------------------------------------------------
 */

$pageTitle = $pageTitle ?? 'FEUreka';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
          rel="stylesheet">

    <!-- Shared Styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/user.css">
    <link rel="stylesheet" href="../../assets/css/modal.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">

</head>

<body>