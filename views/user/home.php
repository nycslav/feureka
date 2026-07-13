<?php declare(strict_types=1);

require_once '../../config/session.php';
require_once '../../includes/functions.php';

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main>

    <?php require_once '../components/hero.php'; ?>

    <?php require_once '../components/search-bar.php'; ?>

    <?php require_once '../components/category-filter.php'; ?>

    <?php

    $items = getApprovedItems();

    ?>

    <section class="items-section">

        <div class="container">

            <h2 class="section-title">

                Recently Found Items

            </h2>

            <div class="items-grid">

                <?php foreach ($items as $item): ?>

                    <?php require '../components/found-item-card.php'; ?>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

</main>

<?php require_once '../../includes/footer.php'; ?>