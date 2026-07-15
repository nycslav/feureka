<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Category Filter
 * ---------------------------------------------------------
 */

$categories = getCategories();

$currentCategory = isset($_GET['category'])
    ? (int) $_GET['category']
    : 0;

$search = $_GET['search'] ?? '';
?>

<section class="category-section">

    <div class="container">

        <div class="category-list">

            <!-- All Items -->

            <form method="GET" action="home.php">

                <?php if ($search !== ''): ?>

                    <input
                        type="hidden"
                        name="search"
                        value="<?= htmlspecialchars((string)$search) ?>">

                <?php endif; ?>

                <button
                    type="submit"
                    class="category-chip <?= $currentCategory === 0 ? 'active' : '' ?>">

                    All Items

                </button>

            </form>

            <?php foreach ($categories as $category): ?>

                <form method="GET" action="home.php">

                    <input
                        type="hidden"
                        name="category"
                        value="<?= (int)$category['category_id'] ?>">

                    <?php if ($search !== ''): ?>

                        <input
                            type="hidden"
                            name="search"
                            value="<?= htmlspecialchars((string)$search) ?>">

                    <?php endif; ?>

                    <button
                        type="submit"
                        class="category-chip <?= $currentCategory === (int)$category['category_id'] ? 'active' : '' ?>">

                        <?= htmlspecialchars($category['category_name']) ?>

                    </button>

                </form>

            <?php endforeach; ?>

        </div>

    </div>

</section>