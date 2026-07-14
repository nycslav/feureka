<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Lost and Found Management System
 * ---------------------------------------------------------
 * File: item-details-modal.php
 * Module: User Interface & Public Pages
 *
 * Purpose:
 * Displays the selected found item.
 * ---------------------------------------------------------
 */

$image = '../../' . $item['image'];

$filePath = __DIR__ . '/../../' . $item['image'];

if (!file_exists($filePath)) {
    $image = '../../assets/images/no-image.png';
}
?>

<div
    class="modal-overlay"
    id="item-modal-<?= (int)$item['item_id']; ?>">

    <div class="item-modal">

        <button
            class="modal-close"
            type="button">

            &times;

        </button>

        <div class="modal-image">

            <img
                src="<?= htmlspecialchars($image); ?>"
                alt="<?= htmlspecialchars($item['item_name']); ?>">

        </div>

        <div class="modal-content">

            <h2>

                <?= htmlspecialchars($item['item_name']); ?>

            </h2>

            <p>

                <strong>Category:</strong>

                <?= htmlspecialchars($item['category_name']); ?>

            </p>

            <p>

                <strong>Description:</strong>

                <?= htmlspecialchars($item['item_description']); ?>

            </p>

            <p>

                <strong>Date Found:</strong>

                <?= htmlspecialchars($item['date_found']); ?>

            </p>

            <p>

                <strong>Location:</strong>

                <?= htmlspecialchars($item['location_description']); ?>

            </p>

            <hr>

            <p>

                To claim this item, please visit the
                <strong>FEU Institute of Technology Lost and Found Office.</strong>

            </p>

        </div>

    </div>

</div>