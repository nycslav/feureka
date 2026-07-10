<?php declare(strict_types=1);

/**
 * ============================================================================
 * FEUreka Shared Functions
 * ============================================================================
 *
 * Reusable database and business logic functions shared by the authentication,
 * user interface, reporting, admin, and integration modules.
 * ============================================================================
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';


/* ============================================================================
 * INTERNAL HELPERS
 * ============================================================================
 *
 * The feureka... functions below are internal helper utilities used only inside
 * this file. They are not part of the public API documented for other modules.
 */

function feurekaConnection(): ?mysqli
{
    global $conn;

    if ($conn instanceof mysqli) {
        return $conn;
    }

    error_log('FEureka database error: reusable $conn is not available.');

    return null;
}

function feurekaLogDatabaseError(string $context, string $message): void
{
    error_log(sprintf('FEureka database %s failed: %s', $context, $message));
}

function feurekaExecuteStatement(string $sql, string $types = '', array $params = []): ?mysqli_stmt
{
    $database = feurekaConnection();

    if ($database === null) {
        return null;
    }

    $stmt = $database->prepare($sql);

    if (!$stmt) {
        feurekaLogDatabaseError('prepare', $database->error);
        return null;
    }

    if ($types !== '') {
        if (strlen($types) !== count($params)) {
            feurekaLogDatabaseError('bind', 'parameter count does not match type definition');
            $stmt->close();
            return null;
        }

        $boundParams = $params;
        $references = [];

        foreach ($boundParams as $key => &$value) {
            $references[$key] = &$value;
        }

        if (!$stmt->bind_param($types, ...$references)) {
            feurekaLogDatabaseError('bind', $stmt->error);
            $stmt->close();
            return null;
        }
    }

    if (!$stmt->execute()) {
        feurekaLogDatabaseError('execute', $stmt->error);
        $stmt->close();
        return null;
    }

    return $stmt;
}

function feurekaFetchAll(string $sql, string $types = '', array $params = []): array
{
    $stmt = feurekaExecuteStatement($sql, $types, $params);

    if ($stmt === null) {
        return [];
    }

    $result = $stmt->get_result();

    if (!$result) {
        feurekaLogDatabaseError('fetch', $stmt->error);
        $stmt->close();
        return [];
    }

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

function feurekaFetchOne(string $sql, string $types = '', array $params = []): ?array
{
    $rows = feurekaFetchAll($sql, $types, $params);

    return $rows[0] ?? null;
}

function feurekaExecuteWrite(string $sql, string $types = '', array $params = []): ?int
{
    $stmt = feurekaExecuteStatement($sql, $types, $params);

    if ($stmt === null) {
        return null;
    }

    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    return $affectedRows;
}

function feurekaText(array $data, string $key): ?string
{
    if (!array_key_exists($key, $data) || $data[$key] === null) {
        return null;
    }

    $value = trim((string) $data[$key]);

    return $value === '' ? null : $value;
}

function feurekaPositiveInt(array $data, string $key): ?int
{
    if (!array_key_exists($key, $data)) {
        return null;
    }

    $value = filter_var($data[$key], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $value === false ? null : $value;
}

function feurekaDate(array $data, string $key): ?string
{
    $value = feurekaText($data, $key);

    if ($value === null) {
        return null;
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    $errors = DateTimeImmutable::getLastErrors();

    if (
        !$date
        || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
    ) {
        return null;
    }

    return $date->format('Y-m-d');
}

function feurekaCalculateStudentExpirationDate(int $yearLevel): string
{
    $yearsUntilCompletion = MAX_YEAR_LEVEL - $yearLevel + 1;

    return sprintf('%04d-06-30', ((int) date('Y')) + $yearsUntilCompletion);
}

function feurekaIsValidRole(string $role): bool
{
    return in_array($role, [ROLE_ADMIN, ROLE_USER], true);
}

function feurekaIsValidUserType(?string $userType): bool
{
    return in_array($userType, [USER_TYPE_STUDENT, USER_TYPE_STAFF], true);
}

function feurekaFoundItemStatuses(): array
{
    return [
        STATUS_PENDING,
        STATUS_APPROVED,
        STATUS_UNDER_REVIEW,
        STATUS_CLAIMED,
        STATUS_ARCHIVED,
        STATUS_REJECTED,
    ];
}

function feurekaMissingReportStatuses(): array
{
    return [
        STATUS_OPEN,
        STATUS_POSSIBLE_MATCH,
        STATUS_RESOLVED,
        STATUS_ARCHIVED,
    ];
}

function feurekaIsAdminUser(int $adminId): bool
{
    $admin = feurekaFetchOne(
        'SELECT user_id FROM users WHERE user_id = ? AND role = ? LIMIT 1',
        'is',
        [$adminId, ROLE_ADMIN]
    );

    return $admin !== null;
}

function feurekaCurrentAdminId(): ?int
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $adminId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($adminId === false || !feurekaIsAdminUser($adminId)) {
        return null;
    }

    return $adminId;
}

function feurekaFoundItemsByStatus(string $status): array
{
    return feurekaFetchAll(
        'SELECT
            fi.item_id,
            fi.user_id,
            fi.category_id,
            c.category_name,
            fi.item_name,
            fi.item_description,
            fi.room,
            fi.floor,
            fi.location_description,
            fi.date_found,
            fi.status,
            fi.image,
            fi.created_at,
            fi.updated_at,
            fi.processed_by,
            fi.admin_notes,
            reporter.first_name AS reporter_first_name,
            reporter.last_name AS reporter_last_name,
            reporter.email AS reporter_email,
            reporter.user_type AS reporter_user_type,
            admin.first_name AS processed_by_first_name,
            admin.last_name AS processed_by_last_name,
            admin.email AS processed_by_email
        FROM found_items AS fi
        INNER JOIN categories AS c ON c.category_id = fi.category_id
        LEFT JOIN users AS reporter ON reporter.user_id = fi.user_id
        LEFT JOIN users AS admin ON admin.user_id = fi.processed_by
        WHERE fi.status = ?
        ORDER BY fi.date_found DESC, fi.created_at DESC',
        's',
        [$status]
    );
}

function feurekaValidStatusForTable(string $table, string $status): bool
{
    if ($table === 'found_items') {
        return in_array($status, feurekaFoundItemStatuses(), true);
    }

    if ($table === 'missing_reports') {
        return in_array($status, feurekaMissingReportStatuses(), true);
    }

    return false;
}


/* ============================================================================
 * AUTHENTICATION
 * ============================================================================
 */

/**
 * Authenticate a user using email and password.
 *
 * @param string $email
 * @param string $password
 * @return bool
 */
function loginUser(string $email, string $password): bool
{
    $email = trim($email);

    if ($email === '' || $password === '') {
        return false;
    }

    $user = feurekaFetchOne(
        'SELECT
            user_id,
            first_name,
            last_name,
            email,
            password_hash,
            role,
            user_type
        FROM users
        WHERE email = ?
        LIMIT 1',
        's',
        [$email]
    );

    if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
        return false;
    }

    regenerateSessionId();

    $_SESSION['user_id'] = (int) $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['email'] = $user['email'];

    return true;
}

/**
 * Register a new user.
 *
 * @param array $userData
 * @return bool
 */
function registerUser(array $userData): bool
{
    $firstName = feurekaText($userData, 'first_name');
    $lastName = feurekaText($userData, 'last_name');
    $email = feurekaText($userData, 'email');
    $password = feurekaText($userData, 'password');
    $role = feurekaText($userData, 'role') ?? ROLE_USER;
    $userType = feurekaText($userData, 'user_type');
    $yearLevel = null;
    $expirationDate = null;

    if (
        $firstName === null
        || $lastName === null
        || $email === null
        || $password === null
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || !feurekaIsValidRole($role)
    ) {
        return false;
    }

    if ($role === ROLE_USER) {
        if (!feurekaIsValidUserType($userType)) {
            return false;
        }

        if ($userType === USER_TYPE_STUDENT) {
            $yearLevel = feurekaPositiveInt($userData, 'year_level');

            if ($yearLevel === null || $yearLevel > MAX_YEAR_LEVEL) {
                return false;
            }

            $expirationDate = feurekaCalculateStudentExpirationDate($yearLevel);
        }
    } else {
        $userType = null;
    }

    if ($userType === USER_TYPE_STAFF) {
        $yearLevel = null;
        $expirationDate = null;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($passwordHash === false) {
        return false;
    }

    $affectedRows = feurekaExecuteWrite(
        'INSERT INTO users (
            first_name,
            last_name,
            email,
            password_hash,
            role,
            user_type,
            year_level,
            expiration_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        'ssssssis',
        [
            $firstName,
            $lastName,
            $email,
            $passwordHash,
            $role,
            $userType,
            $yearLevel,
            $expirationDate,
        ]
    );

    return $affectedRows === 1;
}


/* ============================================================================
 * ITEM RETRIEVAL
 * ============================================================================
 */

/**
 * Retrieve all approved found items.
 *
 * @return array
 */
function getApprovedItems(): array
{
    return feurekaFoundItemsByStatus(STATUS_APPROVED);
}

/**
 * Retrieve all pending found items.
 *
 * @return array
 */
function getPendingItems(): array
{
    return feurekaFoundItemsByStatus(STATUS_PENDING);
}


/* ============================================================================
 * SEARCH & FILTER
 * ============================================================================
 */

/**
 * Search approved found items.
 *
 * @param string $keyword
 * @return array
 */
function searchItems(string $keyword): array
{
    $keyword = trim($keyword);

    if ($keyword === '') {
        return getApprovedItems();
    }

    $searchTerm = sprintf('%%%s%%', $keyword);

    return feurekaFetchAll(
        'SELECT
            fi.item_id,
            fi.user_id,
            fi.category_id,
            c.category_name,
            fi.item_name,
            fi.item_description,
            fi.room,
            fi.floor,
            fi.location_description,
            fi.date_found,
            fi.status,
            fi.image,
            fi.created_at,
            fi.updated_at,
            fi.processed_by,
            fi.admin_notes,
            reporter.first_name AS reporter_first_name,
            reporter.last_name AS reporter_last_name,
            reporter.email AS reporter_email,
            reporter.user_type AS reporter_user_type,
            admin.first_name AS processed_by_first_name,
            admin.last_name AS processed_by_last_name,
            admin.email AS processed_by_email
        FROM found_items AS fi
        INNER JOIN categories AS c ON c.category_id = fi.category_id
        LEFT JOIN users AS reporter ON reporter.user_id = fi.user_id
        LEFT JOIN users AS admin ON admin.user_id = fi.processed_by
        WHERE fi.status = ?
            AND (
                fi.item_name LIKE ?
                OR fi.item_description LIKE ?
                OR fi.room LIKE ?
                OR fi.floor LIKE ?
                OR fi.location_description LIKE ?
                OR c.category_name LIKE ?
            )
        ORDER BY fi.date_found DESC, fi.created_at DESC',
        'sssssss',
        [
            STATUS_APPROVED,
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm,
        ]
    );
}

/**
 * Filter approved found items by category.
 *
 * @param int $categoryId
 * @return array
 */
function filterItems(int $categoryId): array
{
    if ($categoryId <= 0) {
        return getApprovedItems();
    }

    return feurekaFetchAll(
        'SELECT
            fi.item_id,
            fi.user_id,
            fi.category_id,
            c.category_name,
            fi.item_name,
            fi.item_description,
            fi.room,
            fi.floor,
            fi.location_description,
            fi.date_found,
            fi.status,
            fi.image,
            fi.created_at,
            fi.updated_at,
            fi.processed_by,
            fi.admin_notes,
            reporter.first_name AS reporter_first_name,
            reporter.last_name AS reporter_last_name,
            reporter.email AS reporter_email,
            reporter.user_type AS reporter_user_type,
            admin.first_name AS processed_by_first_name,
            admin.last_name AS processed_by_last_name,
            admin.email AS processed_by_email
        FROM found_items AS fi
        INNER JOIN categories AS c ON c.category_id = fi.category_id
        LEFT JOIN users AS reporter ON reporter.user_id = fi.user_id
        LEFT JOIN users AS admin ON admin.user_id = fi.processed_by
        WHERE fi.status = ?
            AND fi.category_id = ?
        ORDER BY fi.date_found DESC, fi.created_at DESC',
        'si',
        [STATUS_APPROVED, $categoryId]
    );
}


/* ============================================================================
 * ITEM REPORTING
 * ============================================================================
 */

/**
 * Submit a found item report.
 *
 * @param array $itemData
 * @return bool
 */
function submitFoundItem(array $itemData): bool
{
    $userId = feurekaPositiveInt($itemData, 'user_id');
    $categoryId = feurekaPositiveInt($itemData, 'category_id');
    $itemName = feurekaText($itemData, 'item_name');
    $itemDescription = feurekaText($itemData, 'item_description');
    $room = feurekaText($itemData, 'room');
    $floor = feurekaText($itemData, 'floor');
    $locationDescription = feurekaText($itemData, 'location_description');
    $dateFound = feurekaDate($itemData, 'date_found');
    $image = feurekaText($itemData, 'image');

    if (isset($_SESSION['user_id']) && $userId === null) {
        $userId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $userId = $userId === false ? null : $userId;
    }

    if (
        $userId === null
        || $categoryId === null
        || $itemName === null
        || $itemDescription === null
        || $dateFound === null
    ) {
        return false;
    }

    $processedBy = null;
    $adminNotes = null;

    $affectedRows = feurekaExecuteWrite(
        'INSERT INTO found_items (
            user_id,
            category_id,
            item_name,
            item_description,
            room,
            floor,
            location_description,
            date_found,
            status,
            image,
            processed_by,
            admin_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        'iissssssssis',
        [
            $userId,
            $categoryId,
            $itemName,
            $itemDescription,
            $room,
            $floor,
            $locationDescription,
            $dateFound,
            STATUS_PENDING,
            $image,
            $processedBy,
            $adminNotes,
        ]
    );

    return $affectedRows === 1;
}

/**
 * Submit a missing item report.
 *
 * @param array $reportData
 * @return bool
 */
function submitMissingReport(array $reportData): bool
{
    $userId = feurekaPositiveInt($reportData, 'user_id');
    $categoryId = feurekaPositiveInt($reportData, 'category_id');
    $itemName = feurekaText($reportData, 'item_name');
    $itemDescription = feurekaText($reportData, 'item_description');
    $room = feurekaText($reportData, 'room');
    $floor = feurekaText($reportData, 'floor');
    $locationDescription = feurekaText($reportData, 'location_description');
    $dateLost = feurekaDate($reportData, 'date_lost');
    $contactNumber = feurekaText($reportData, 'contact_number');
    $image = feurekaText($reportData, 'image');

    if (isset($_SESSION['user_id']) && $userId === null) {
        $userId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $userId = $userId === false ? null : $userId;
    }

    if (
        $userId === null
        || $categoryId === null
        || $itemName === null
        || $itemDescription === null
        || $dateLost === null
        || $contactNumber === null
    ) {
        return false;
    }

    $processedBy = null;
    $adminNotes = null;

    $affectedRows = feurekaExecuteWrite(
        'INSERT INTO missing_reports (
            user_id,
            category_id,
            item_name,
            item_description,
            room,
            floor,
            location_description,
            date_lost,
            contact_number,
            status,
            image,
            processed_by,
            admin_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        'iisssssssssis',
        [
            $userId,
            $categoryId,
            $itemName,
            $itemDescription,
            $room,
            $floor,
            $locationDescription,
            $dateLost,
            $contactNumber,
            STATUS_OPEN,
            $image,
            $processedBy,
            $adminNotes,
        ]
    );

    return $affectedRows === 1;
}


/* ============================================================================
 * ADMIN FUNCTIONS
 * ============================================================================
 */

/**
 * Approve a pending found item.
 *
 * @param int $itemId
 * @param int $adminId
 * @return bool
 */
function approveItem(int $itemId, int $adminId): bool
{
    if ($itemId <= 0 || $adminId <= 0 || !feurekaIsAdminUser($adminId)) {
        return false;
    }

    $affectedRows = feurekaExecuteWrite(
        'UPDATE found_items
        SET status = ?, processed_by = ?
        WHERE item_id = ? AND status = ?',
        'siis',
        [STATUS_APPROVED, $adminId, $itemId, STATUS_PENDING]
    );

    return $affectedRows === 1;
}

/**
 * Update the status of a found item or missing report.
 *
 * @param string $table
 * @param int $recordId
 * @param string $status
 * @param int $adminId
 * @param string|null $notes
 * @return bool
 */
function updateItemStatus(
    string $table,
    int $recordId,
    string $status,
    int $adminId,
    ?string $notes = null
): bool {
    $table = trim($table);
    $status = trim($status);
    $notes = $notes === null ? null : trim($notes);
    $notes = $notes === '' ? null : $notes;

    if (
        $recordId <= 0
        || $adminId <= 0
        || !feurekaValidStatusForTable($table, $status)
        || !feurekaIsAdminUser($adminId)
    ) {
        return false;
    }

    if ($table === 'found_items') {
        $affectedRows = feurekaExecuteWrite(
            'UPDATE found_items
            SET status = ?, processed_by = ?, admin_notes = ?
            WHERE item_id = ?',
            'sisi',
            [$status, $adminId, $notes, $recordId]
        );

        return $affectedRows === 1;
    }

    if ($table === 'missing_reports') {
        $affectedRows = feurekaExecuteWrite(
            'UPDATE missing_reports
            SET status = ?, processed_by = ?, admin_notes = ?
            WHERE report_id = ?',
            'sisi',
            [$status, $adminId, $notes, $recordId]
        );

        return $affectedRows === 1;
    }

    return false;
}

/**
 * Archive a found item or missing report.
 *
 * @param string $table
 * @param int $recordId
 * @return bool
 */
function archiveItem(string $table, int $recordId): bool
{
    $table = trim($table);
    $adminId = feurekaCurrentAdminId();

    if ($recordId <= 0 || $adminId === null) {
        return false;
    }

    if ($table === 'found_items') {
        $affectedRows = feurekaExecuteWrite(
            'UPDATE found_items
            SET status = ?, processed_by = ?
            WHERE item_id = ?',
            'sii',
            [STATUS_ARCHIVED, $adminId, $recordId]
        );

        return $affectedRows === 1;
    }

    if ($table === 'missing_reports') {
        $affectedRows = feurekaExecuteWrite(
            'UPDATE missing_reports
            SET status = ?, processed_by = ?
            WHERE report_id = ?',
            'sii',
            [STATUS_ARCHIVED, $adminId, $recordId]
        );

        return $affectedRows === 1;
    }

    return false;
}

/**
 * Retrieve dashboard statistics.
 *
 * @return array
 */
function getDashboardCounts(): array
{
    $counts = feurekaFetchOne(
        'SELECT
            (
                SELECT COUNT(*)
                FROM found_items
                WHERE status = ?
            ) AS pending_found_items,
            (
                SELECT COUNT(*)
                FROM found_items
                WHERE status = ?
            ) AS approved_found_items,
            (
                SELECT COUNT(*)
                FROM missing_reports
            ) AS missing_reports,
            (
                (
                    SELECT COUNT(*)
                    FROM found_items
                    WHERE status = ?
                )
                +
                (
                    SELECT COUNT(*)
                    FROM missing_reports
                    WHERE status = ?
                )
            ) AS archived_records',
        'ssss',
        [
            STATUS_PENDING,
            STATUS_APPROVED,
            STATUS_ARCHIVED,
            STATUS_ARCHIVED,
        ]
    );

    return [
        'pending_found_items' => (int) ($counts['pending_found_items'] ?? 0),
        'approved_found_items' => (int) ($counts['approved_found_items'] ?? 0),
        'missing_reports' => (int) ($counts['missing_reports'] ?? 0),
        'archived_records' => (int) ($counts['archived_records'] ?? 0),
    ];
}


/* ============================================================================
 * ACCOUNT LIFECYCLE
 * ============================================================================
 */

/**
 * Delete expired student accounts.
 *
 * Student accounts whose expiration_date has passed are permanently removed.
 * Related found_items.user_id and missing_reports.user_id are automatically
 * set to NULL through ON DELETE SET NULL.
 *
 * @return int Number of deleted accounts.
 */
function deleteExpiredUsers(): int
{
    $database = feurekaConnection();

    if ($database === null || !$database->begin_transaction()) {
        return 0;
    }

    $affectedRows = feurekaExecuteWrite(
        'DELETE FROM users
        WHERE role = ?
            AND user_type = ?
            AND expiration_date IS NOT NULL
            AND expiration_date <= CURRENT_DATE',
        'ss',
        [ROLE_USER, USER_TYPE_STUDENT]
    );

    if ($affectedRows === null) {
        $database->rollback();
        return 0;
    }

    if (!$database->commit()) {
        feurekaLogDatabaseError('commit', $database->error);
        $database->rollback();
        return 0;
    }

    return $affectedRows;
}

/**
 * Delete a staff account.
 *
 * Used only by administrators from the User Management page.
 *
 * @param int $userId
 * @return bool
 */
function deleteStaffAccount(int $userId): bool
{
    if ($userId <= 0) {
        return false;
    }

    $database = feurekaConnection();

    if ($database === null || !$database->begin_transaction()) {
        return false;
    }

    $affectedRows = feurekaExecuteWrite(
        'DELETE FROM users
        WHERE user_id = ?
            AND role = ?
            AND user_type = ?',
        'iss',
        [$userId, ROLE_USER, USER_TYPE_STAFF]
    );

    if ($affectedRows === null) {
        $database->rollback();
        return false;
    }

    if (!$database->commit()) {
        feurekaLogDatabaseError('commit', $database->error);
        $database->rollback();
        return false;
    }

    return $affectedRows === 1;
}
