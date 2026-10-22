<?php

/**
 * Идентификаторы истёкших лотов, которым ещё не назначен победитель.
 * @param mysqli $conn
 * @return false|string|null
 */
function getExpiredLotIds(mysqli $conn)
{
    $sql = "SELECT GROUP_CONCAT(id) AS lot_ids
        FROM lots
        WHERE winner IS NULL AND expiry_dt < NOW()";

    $result = fetchOneFromDb($conn, $sql);

    if ($result === false) {
        return false;
    }

    return $result['lot_ids'];
}
