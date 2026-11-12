<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return array|false
 */
function getCategoryById(mysqli $conn, string $category_id)
{
    $sql = "SELECT id, title FROM categories c WHERE c.id = ?";

    $rows = fetchFromDbByParams($conn, $sql, [$category_id]);

    if (!is_array($rows) || !isset($rows[0])) {
        return null;
    }

    return $rows[0];
}
