<?php declare(strict_types=1);

/**
 * Reusable FEureka database connection.
 *
 * Include this file wherever database access is needed:
 * require_once __DIR__ . '/database.php';
 *
 * The connection is exposed as $conn.
 */

require_once __DIR__ . '/constants.php';

defined('DB_HOST') || define('DB_HOST', getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost');
defined('DB_NAME') || define('DB_NAME', getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'feureka');
defined('DB_USER') || define('DB_USER', getenv('DB_USER') !== false ? getenv('DB_USER') : 'root');
defined('DB_PASS') || define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

if (!extension_loaded('mysqli')) {
    error_log('FEureka database error: mysqli extension is not loaded.');

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(500);
    }

    exit('Database service is currently unavailable.');
}

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_errno !== 0) {
    error_log('FEureka database connection failed: ' . $conn->connect_error);

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(500);
    }

    exit('Unable to connect to the database. Please try again later.');
}

if (!$conn->set_charset('utf8mb4')) {
    error_log('FEureka database charset error: ' . $conn->error);

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(500);
    }

    exit('Unable to configure the database connection. Please try again later.');
}
