<?php

/**
 * Идентификаторы истёкших лотов, которым ещё не назначен победитель.
 * @param mysqli $conn
 * @return array<int, int>|false
 */
function getExpiredLotIds(mysqli $conn): array|false
{
    $sql = "SELECT id
        FROM lots
        WHERE winner IS NULL AND expiry_dt < NOW()";

    $rows = fetchFromDb($conn, $sql);

    if ($rows === false) {
        return false;
    }

    return array_map('intval', array_column($rows, 'id'));
}
