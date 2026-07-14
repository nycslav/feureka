<?php
declare(strict_types=1);

require_once '../../config/database.php';
require_once '../../includes/functions.php';

/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
|
| Retrieve all available item categories from the database.
| This is read-only and keeps the dropdown synchronized with the database.
|
*/

$categories = [];

$result = $conn->query("
    SELECT category_id, category_name
    FROM categories
    ORDER BY category_name ASC
");

if ($result) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Report Found Item</title>

    <!-- CSS will be added later by Member 2 -->
</head>

<body>

<h1>Report Found Item</h1>

<form
    id="foundItemForm"
    action="../../actions/submit-found-item.php"
    method="POST"
    enctype="multipart/form-data">

    <!-- Category -->
    <div>
        <label for="category_id">Category</label>

        <select
            id="category_id"
            name="category_id"
            required>

            <option value="">-- Select Category --</option>

            <?php foreach ($categories as $category): ?>

                <option value="<?= htmlspecialchars($category['category_id']) ?>">
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <small class="error-message"></small>
    </div>

    <!-- Item Name -->
    <div>

        <label for="item_name">Item Name</label>

        <input
            type="text"
            id="item_name"
            name="item_name"
            required>

        <small class="error-message"></small>

    </div>

    <!-- Description -->
    <div>

        <label for="item_description">
            Description
        </label>

        <textarea
            id="item_description"
            name="item_description"
            rows="4"
            required></textarea>

        <small class="error-message"></small>

    </div>

    <!-- Room -->
    <div>

        <label for="room">
            Room
        </label>

        <input
            type="text"
            id="room"
            name="room"
            required>

        <small class="error-message"></small>

    </div>

    <!-- Floor -->
    <div>

        <label for="floor">
            Floor
        </label>

        <input
            type="text"
            id="floor"
            name="floor"
            required>

        <small class="error-message"></small>

    </div>

    <!-- Location Description -->
    <div>

        <label for="location_description">
            Location Description
        </label>

        <textarea
            id="location_description"
            name="location_description"
            rows="3"
            required></textarea>

        <small class="error-message"></small>

    </div>

    <!-- Date Found -->
    <div>

        <label for="date_found">
            Date Found
        </label>

        <input
            type="date"
            id="date_found"
            name="date_found"
            required>

        <small class="error-message"></small>

    </div>

    <!-- Image -->
    <div>

        <label for="image">
            Upload Image
        </label>

        <input
            type="file"
            id="image"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            required>

        <small class="error-message"></small>

    </div>

    <button type="submit">
        Submit Report
    </button>

</form>

<script src="../../assets/js/validation.js"></script>

</body>
</html>