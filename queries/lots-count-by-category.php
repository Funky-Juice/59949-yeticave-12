<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return null|string
 */
function getLotsCountByCategory(mysqli $conn, string $category_id): ?string
{
    $sql = "SELECT COUNT(*) AS lots_count FROM lots l WHERE l.category_id = ? AND l.expiry_dt > NOW()";

    $result = fetchFromDbByParams($conn, $sql, [$category_id]);

    if (is_array($result) && isset($result[0]['lots_count'])) {
        return $result[0]['lots_count'];
    }

    return null;
}
