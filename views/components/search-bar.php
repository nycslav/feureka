<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Lost and Found Management System
 * ---------------------------------------------------------
 * File: search-bar.php
 * Module: User Interface & Public Pages
 *
 * Purpose:
 * Displays the global search bar.
 * ---------------------------------------------------------
 */

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
?>

<section class="search-section">

    <div class="container">

        <form
            class="search-form"
            method="GET"
            action="home.php">

            <?php if ($category !== ''): ?>

                <input
                    type="hidden"
                    name="category"
                    value="<?= htmlspecialchars((string)$category) ?>">

            <?php endif; ?>

            <span class="material-symbols-outlined search-icon">
                search
            </span>

            <input
                id="search-input"
                type="search"
                name="search"
                class="search-input"
                value="<?= htmlspecialchars((string)$search) ?>"
                placeholder="Search lost and found items...">

        </form>

    </div>

</section>