<?php

/**
 * @param mysqli $conn
 * @param string $search_str
 * @return null|string
 */
function getLotsCountBySearch(mysqli $conn, string $search_str): ?string
{
    $sql = "SELECT COUNT(*) AS lots_count
    FROM lots l
    WHERE MATCH(l.title, description) AGAINST(? IN BOOLEAN MODE)
    AND l.expiry_dt > NOW()";

    $result = fetchFromDbByParams($conn, $sql, [$search_str]);

    if (is_array($result) && isset($result[0]['lots_count'])) {
        return $result[0]['lots_count'];
    }

    return null;
}
