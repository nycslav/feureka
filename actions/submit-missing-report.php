<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Submit Missing Report Action
 * ============================================================================
 *
 * Processes the Report Missing Item form, validates the uploaded image
 * (optional), prepares the report data, and submits it through the shared
 * submitMissingReport() helper.
 * ============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';

/* ============================================================================
 * REQUEST VALIDATION
 * ========================================================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/user/report-missing-item.php');
    exit;
}

requireLogin();

/* ============================================================================
 * USER VALIDATION
 * ========================================================================== */

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to submit a report.';
    header('Location: ../views/auth/login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];

/* ============================================================================
 * FORM DATA
 * ========================================================================== */

$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$itemName = trim($_POST['item_name'] ?? '');
$itemDescription = trim($_POST['item_description'] ?? '');
$room = trim($_POST['room'] ?? '');
$floor = trim($_POST['floor'] ?? '');
$locationDescription = trim($_POST['location_description'] ?? '');
$dateLost = trim($_POST['date_lost'] ?? '');
$contactNumber = trim($_POST['contact_number'] ?? '');

/* ============================================================================
 * REQUIRED FIELD VALIDATION
 * ========================================================================== */

if (
    $categoryId === false ||
    $itemName === '' ||
    $itemDescription === '' ||
    $room === '' ||
    $floor === '' ||
    $locationDescription === '' ||
    $dateLost === '' ||
    $contactNumber === ''
) {
    $_SESSION['error'] = 'Please complete all required fields.';
    header('Location: ../views/user/report-missing-item.php');
    exit;
}

/* ============================================================================
 * DATE VALIDATION
 * ========================================================================== */

$dateObject = DateTimeImmutable::createFromFormat('!Y-m-d', $dateLost);
$dateErrors = DateTimeImmutable::getLastErrors();

if (
    !$dateObject ||
    ($dateErrors !== false &&
        ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
) {
    $_SESSION['error'] = 'Please provide a valid date.';
    header('Location: ../views/user/report-missing-item.php');
    exit;
}

$today = new DateTimeImmutable('today');

if ($dateObject > $today) {
    $_SESSION['error'] = 'Date cannot be in the future.';
    header('Location: ../views/user/report-missing-item.php');
    exit;
}

/* ============================================================================
 * CONTACT NUMBER VALIDATION
 * ========================================================================== */

// Remove everything except digits
$contactNumber = preg_replace('/\D/', '', $contactNumber);

// Validate Philippine mobile number
if (!preg_match('/^09\d{9}$/', $contactNumber)) {
    $_SESSION['error'] = 'Please enter a valid contact number.';
    header('Location: ../views/user/report-missing-item.php');
    exit;
}

// Format as 0917-123-4567
$contactNumber = substr($contactNumber, 0, 4)
    . '-'
    . substr($contactNumber, 4, 3)
    . '-'
    . substr($contactNumber, 7, 4);

/* ============================================================================
 * OPTIONAL IMAGE UPLOAD
 * ========================================================================== */

$imagePath = null;

if (
    isset($_FILES['image']) &&
    is_array($_FILES['image']) &&
    $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $imageFile = $_FILES['image'];

    if ($imageFile['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'There was a problem uploading the image.';
        header('Location: ../views/user/report-missing-item.php');
        exit;
    }

    if ($imageFile['size'] > MAX_IMAGE_SIZE) {
        $_SESSION['error'] = 'Image size must not exceed 5 MB.';
        header('Location: ../views/user/report-missing-item.php');
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($imageFile['tmp_name']);

    if (
        $mimeType === false ||
        !in_array($mimeType, ALLOWED_IMAGE_TYPES, true)
    ) {
        $_SESSION['error'] = 'Only JPG, JPEG, PNG, GIF and WEBP files are allowed.';
        header('Location: ../views/user/report-missing-item.php');
        exit;
    }

    $originalName = pathinfo(
        $imageFile['name'],
        PATHINFO_FILENAME
    );

    $safeName = strtolower(
        preg_replace('/[^a-zA-Z0-9]+/', '-', $originalName)
    );

    $safeName = trim($safeName, '-');

    if ($safeName === '') {
        $safeName = 'missing-item';
    }

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg',
    };

    $timestamp = date('Ymd-His');

    $fileName = sprintf(
        '%s-%s.%s',
        $safeName,
        $timestamp,
        $extension
    );

    if (
        !is_dir(UPLOAD_DIRECTORY)
        && !mkdir(UPLOAD_DIRECTORY, 0775, true)
        && !is_dir(UPLOAD_DIRECTORY)
    ) {
        $_SESSION['error'] = 'Unable to prepare the upload directory.';
        header('Location: ../views/user/report-missing-item.php');
        exit;
    }

    $destination = rtrim(
        UPLOAD_DIRECTORY,
        DIRECTORY_SEPARATOR
    ) . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($imageFile['tmp_name'], $destination)) {
        $_SESSION['error'] = 'Unable to save the uploaded image.';
        header('Location: ../views/user/report-missing-item.php');
        exit;
    }

    $imagePath = 'assets/uploads/' . $fileName;
}

/* ============================================================================
 * PREPARE REPORT DATA
 * ========================================================================== */

$reportData = [

    'user_id' => $userId,

    'category_id' => (int) $categoryId,

    'item_name' => $itemName,

    'item_description' => $itemDescription,

    'room' => $room,

    'floor' => $floor,

    'location_description' => $locationDescription,

    'date_lost' => $dateLost,

    'contact_number' => $contactNumber,

    'image' => $imagePath,

];

/* ============================================================================
 * SAVE REPORT
 * ========================================================================== */

if (!submitMissingReport($reportData)) {

    if ($imagePath !== null) {

        $uploadedFile = rtrim(
            UPLOAD_DIRECTORY,
            DIRECTORY_SEPARATOR
        ) . DIRECTORY_SEPARATOR . basename($imagePath);

        if (is_file($uploadedFile)) {
            @unlink($uploadedFile);
        }

    }

    $_SESSION['error'] = 'Unable to submit the report. Please try again later.';

    header('Location: ../views/user/report-missing-item.php');
    exit;
}

/* ============================================================================
 * SUCCESS
 * ========================================================================== */

$_SESSION['success'] = 'Report submitted successfully.';

header('Location: ../views/user/report-missing-item.php');
exit;
