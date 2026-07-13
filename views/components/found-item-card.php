<article class="item-card">

    <div class="item-image">

        <?php

        $image = '../../' . $item['image'];

        if (!file_exists(__DIR__ . '/../../' . $item['image'])) {
            $image = '../../assets/images/no-image.png';
        }

        ?>

        <img
            src="<?= htmlspecialchars($image) ?>"
            alt="<?= htmlspecialchars($item['item_name']) ?>">

        <span class="item-badge">

            <?= $item['status'] === 'Approved'
                ? 'Found'
                : htmlspecialchars($item['status']) ?>

        </span>

    </div>

    <div class="item-body">

        <h3 class="item-title">

            <?= htmlspecialchars($item['item_name']) ?>

        </h3>

        <p class="item-category">

            <?= htmlspecialchars($item['category_name']) ?>

        </p>

        <div class="item-meta">

            <div class="item-meta-row">

                <span class="material-symbols-outlined">
                    calendar_today
                </span>

                <span>
                    <?= htmlspecialchars($item['date_found']) ?>
                </span>

            </div>

            <div class="item-meta-row">

                <span class="material-symbols-outlined">
                    location_on
                </span>

                <span>
                    <?= htmlspecialchars($item['location_description']) ?>
                </span>

            </div>

        </div>

        <button
            class="btn btn-primary item-button"
            type="button"
            data-item-id="<?= htmlspecialchars((string)$item['item_id']) ?>">

            View Details

        </button>

    </div>

</article>