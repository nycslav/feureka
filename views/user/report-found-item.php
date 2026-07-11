<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Found Item | FEUreka</title>
</head>
<body>

<main>
    <section>
        <h1>Report Found Item</h1>
        <p>
            Found an item inside FEU Tech?
            Complete the form below and submit it for administrator review.
        </p>

        <form
            id="foundItemForm"
            action="../../actions/submit-found-item.php"
            method="POST"
            enctype="multipart/form-data">

            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars((string) $category['category_id']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="item_name">Item Name</label>
                <input
                    type="text"
                    id="item_name"
                    name="item_name"
                    maxlength="150"
                    required>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="item_description">Description</label>
                <textarea
                    id="item_description"
                    name="item_description"
                    rows="5"
                    required></textarea>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="room">Room</label>
                <input
                    type="text"
                    id="room"
                    name="room"
                    maxlength="100"
                    placeholder="Example: Room 402"
                    required>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="floor">Floor</label>
                <input
                    type="text"
                    id="floor"
                    name="floor"
                    maxlength="50"
                    placeholder="Example: 4th Floor"
                    required>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="location_description">Location Description</label>
                <textarea
                    id="location_description"
                    name="location_description"
                    rows="4"
                    placeholder="Describe exactly where the item was found."
                    required></textarea>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="date_found">Date Found</label>
                <input
                    type="date"
                    id="date_found"
                    name="date_found"
                    required>
                <small class="error-message"></small>
            </div>

            <div>
                <label for="image">Upload Image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required>
                <small class="error-message"></small>
            </div>

            <div>
                <button type="submit">Submit Report</button>
                <button type="reset">Clear Form</button>
            </div>
        </form>
    </section>
</main>

<script src="../../assets/js/validation.js"></script>
</body>
</html>