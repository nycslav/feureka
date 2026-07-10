<?php declare(strict_types=1);

/**
 * Central FEureka system constants.
 *
 * Shared values are here so authentication, reporting, admin, and integration
 * modules use the same documented labels and rules.
 */

date_default_timezone_set('Asia/Manila');

// User roles.
defined('ROLE_ADMIN') || define('ROLE_ADMIN', 'Admin');
defined('ROLE_USER') || define('ROLE_USER', 'User');

// User account types.
defined('USER_TYPE_STUDENT') || define('USER_TYPE_STUDENT', 'Student');
defined('USER_TYPE_STAFF') || define('USER_TYPE_STAFF', 'Staff');

// Found item workflow statuses.
defined('STATUS_PENDING') || define('STATUS_PENDING', 'Pending');
defined('STATUS_APPROVED') || define('STATUS_APPROVED', 'Approved');
defined('STATUS_UNDER_REVIEW') || define('STATUS_UNDER_REVIEW', 'Under Review');
defined('STATUS_CLAIMED') || define('STATUS_CLAIMED', 'Claimed');
defined('STATUS_ARCHIVED') || define('STATUS_ARCHIVED', 'Archived');
defined('STATUS_REJECTED') || define('STATUS_REJECTED', 'Rejected');

// Missing report workflow statuses.
defined('STATUS_OPEN') || define('STATUS_OPEN', 'Open');
defined('STATUS_POSSIBLE_MATCH') || define('STATUS_POSSIBLE_MATCH', 'Possible Match');
defined('STATUS_RESOLVED') || define('STATUS_RESOLVED', 'Resolved');

// Upload settings for documented item image uploads.
defined('UPLOAD_DIRECTORY') || define('UPLOAD_DIRECTORY', dirname(__DIR__) . '/assets/uploads/');
defined('MAX_IMAGE_SIZE') || define('MAX_IMAGE_SIZE', 5 * 1024 * 1024);
defined('ALLOWED_IMAGE_TYPES') || define('ALLOWED_IMAGE_TYPES', [
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
]);

// Student academic lifecycle rule.
defined('MAX_YEAR_LEVEL') || define('MAX_YEAR_LEVEL', 4);
