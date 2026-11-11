<?php

/**
 * Окно лимита неудачного входа, минут.
 */
const LOGIN_ATTEMPT_WINDOW_MINUTES = 15;

/**
 * Сколько неудачных попыток допускается в окне.
 * Считается отдельно для связки IP + email и для IP.
 */
const LOGIN_ATTEMPT_LIMIT = 5;

/**
 * Удаляет попытки старше суток, затем проверяет лимит за 15 минут.
 * Лимит исчерпан, если попыток не меньше 5 на связку IP + email или на IP.
 *
 * @param mysqli $conn
 * @param string $ip
 * @param string $email
 * @return array{blocked: bool, retry_after_minutes: int}|false
 */
function checkLoginAttempts(mysqli $conn, string $ip, string $email)
{
    $deleted = mysqli_query(
        $conn,
        "DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY)"
    );

    if (!$deleted) {
        return false;
    }

    $sql = "SELECT email,
                   TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(attempted_at, INTERVAL " . LOGIN_ATTEMPT_WINDOW_MINUTES . " MINUTE)) AS seconds_left
            FROM login_attempts
            WHERE ip = ?
              AND attempted_at >= DATE_SUB(NOW(), INTERVAL " . LOGIN_ATTEMPT_WINDOW_MINUTES . " MINUTE)
            ORDER BY attempted_at ASC";

    $rows = fetchFromDbByParams($conn, $sql, [$ip]);

    if ($rows === false) {
        return false;
    }

    $ip_seconds = [];
    $pair_seconds = [];

    foreach ($rows as $row) {
        $seconds = (int) ($row['seconds_left'] ?? 0);
        $ip_seconds[] = $seconds;

        if (strcasecmp((string) ($row['email'] ?? ''), $email) === 0) {
            $pair_seconds[] = $seconds;
        }
    }

    $retry_after_minutes = max(
        loginAttemptRetryMinutes($ip_seconds),
        loginAttemptRetryMinutes($pair_seconds)
    );

    return [
        'blocked' => $retry_after_minutes > 0,
        'retry_after_minutes' => $retry_after_minutes,
    ];
}

/**
 * Записывает неудачную попытку входа.
 *
 * @param mysqli $conn
 * @param string $ip
 * @param string $email
 * @return false|int|string
 */
function recordLoginAttempt(mysqli $conn, string $ip, string $email)
{
    $sql = "INSERT INTO login_attempts (ip, email) VALUES (?, ?)";

    return dbInsert($conn, $sql, [$ip, $email]);
}

/**
 * Сбрасывает попытки этого IP и этого email после успешного входа.
 *
 * @param mysqli $conn
 * @param string $ip
 * @param string $email
 * @return bool
 */
function resetLoginAttempts(mysqli $conn, string $ip, string $email): bool
{
    $sql = "DELETE FROM login_attempts WHERE ip = ? OR email = ?";
    $stmt = dbGetPrepareStmt($conn, $sql, [$ip, $email]);
    $result = mysqli_stmt_execute($stmt);
    $error = mysqli_stmt_error($stmt);

    if ($error) {
        showDbError($error);
        exit();
    }

    return $result;
}

/**
 * Минуты до снятия лимита по отсортированному списку оставшихся секунд окна.
 * Берётся попытка, после выхода которой в окне останется меньше лимита.
 *
 * @param array $seconds_left
 * @return int
 */
function loginAttemptRetryMinutes(array $seconds_left): int
{
    $total = count($seconds_left);

    if ($total < LOGIN_ATTEMPT_LIMIT) {
        return 0;
    }

    $critical = (int) $seconds_left[$total - LOGIN_ATTEMPT_LIMIT];
    $minutes = (int) ceil(max(0, $critical) / 60);

    return max(1, $minutes);
}
