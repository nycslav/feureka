<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Lost and Found Management System
 * ---------------------------------------------------------
 * File: category-filter.php
 * Module: User Interface & Public Pages
 *
 * Purpose:
 * Displays category filters for found items.
 * ---------------------------------------------------------
 */

$categories = getCategories();
?>

<section class="category-section">

    <div class="container">

        <div class="category-list">

            <button class="category-chip active">
                All Items
            </button>

            <?php foreach ($categories as $category): ?>

                <button class="category-chip">

                    <?= htmlspecialchars($category['category_name']) ?>

                </button>

            <?php endforeach; ?>

        </div>

    </div>

</section>