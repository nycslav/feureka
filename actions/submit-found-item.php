<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Submit Found Item Action
 * ============================================================================
 *
 * Processes the Report Found Item form, validates the uploaded image,
 * prepares the item data, and submits it through the shared helper function.
 * ============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/user/report-found-item.php');
    exit;
}

requireLogin();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to submit a report.';
    header('Location: ../views/auth/login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];

$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$itemName = trim($_POST['item_name'] ?? '');
$itemDescription = trim($_POST['item_description'] ?? '');
$room = trim($_POST['room'] ?? '');
$floor = trim($_POST['floor'] ?? '');
$locationDescription = trim($_POST['location_description'] ?? '');
$dateFound = trim($_POST['date_found'] ?? '');

if (
    $categoryId === false
    || $itemName === ''
    || $itemDescription === ''
    || $room === ''
    || $floor === ''
    || $locationDescription === ''
    || $dateFound === ''
) {
    $_SESSION['error'] = 'Please complete all required fields.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$dateObject = DateTimeImmutable::createFromFormat('Y-m-d', $dateFound);
$dateErrors = DateTimeImmutable::getLastErrors();

if (
    !$dateObject
    || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
) {
    $_SESSION['error'] = 'Please provide a valid date.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$today = new DateTimeImmutable('today');
if ($dateObject > $today) {
    $_SESSION['error'] = 'Date cannot be in the future.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
    $_SESSION['error'] = 'Please upload an image.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$imageFile = $_FILES['image'];

if (($imageFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'There was a problem uploading the image.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

if (($imageFile['size'] ?? 0) > MAX_IMAGE_SIZE) {
    $_SESSION['error'] = 'Image size must not exceed 5 MB.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$allowedMimeTypes = ALLOWED_IMAGE_TYPES;
$finfo = new finfo(FILEINFO_MIME_TYPE);
$detectedMimeType = $finfo->file($imageFile['tmp_name'] ?? '');

if ($detectedMimeType === false || !in_array($detectedMimeType, $allowedMimeTypes, true)) {
    $_SESSION['error'] = 'Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$originalName = pathinfo((string) ($imageFile['name'] ?? 'uploaded-image'), PATHINFO_FILENAME);
$sanitizedName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $originalName) ?? 'uploaded-image');
$sanitizedName = trim($sanitizedName, '-');

if ($sanitizedName === '') {
    $sanitizedName = 'found-item';
}

$extension = match ($detectedMimeType) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
    default => 'jpg',
};

$timestamp = date('Ymd-His');
$fileName = sprintf('%s-%s.%s', $sanitizedName, $timestamp, $extension);
$destinationPath = rtrim(UPLOAD_DIRECTORY, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

if (!is_dir(UPLOAD_DIRECTORY) && !mkdir(UPLOAD_DIRECTORY, 0775, true) && !is_dir(UPLOAD_DIRECTORY)) {
    $_SESSION['error'] = 'Unable to prepare the upload directory.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

if (!move_uploaded_file($imageFile['tmp_name'], $destinationPath)) {
    $_SESSION['error'] = 'Unable to save the uploaded image.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$imagePath = 'assets/uploads/' . $fileName;

$itemData = [
    'user_id' => $userId,
    'category_id' => (int) $categoryId,
    'item_name' => $itemName,
    'item_description' => $itemDescription,
    'room' => $room,
    'floor' => $floor,
    'location_description' => $locationDescription,
    'date_found' => $dateFound,
    'image' => $imagePath,
];


if (!submitFoundItem($itemData)) {
    if (is_file($destinationPath)) {
        @unlink($destinationPath);
    }

    $_SESSION['error'] = 'Unable to submit the report. Please try again.';
    header('Location: ../views/user/report-found-item.php');
    exit;
}

$_SESSION['success'] = 'Report submitted successfully.';
header('Location: ../views/user/report-found-item.php');
exit;