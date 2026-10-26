<?php

/**
 * @param mysqli $conn
 * @param array $ids
 * @return array|false
 */
function getWinnersInfoByIds(mysqli $conn, array $ids)
{
    $ids = array_values(array_filter(
        array_map('intval', $ids),
        static fn(int $id): bool => $id > 0
    ));

    if (!$ids) {
        return [];
    }

    $placeholders = implode(', ', array_fill(0, count($ids), '?'));

    $sql = "SELECT SQ.id AS lot_id, u.id AS user_id, title, name, email
    FROM (
        SELECT id, title, winner FROM lots WHERE id IN ($placeholders)
    ) AS SQ
    JOIN users u ON u.id = SQ.winner";

    return fetchFromDbByParams($conn, $sql, $ids);
}
