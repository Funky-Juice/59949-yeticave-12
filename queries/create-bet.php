<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function create_bet(mysqli $conn, array $data) {
    $placeholders_list = array_fill(0, count($data), '?');
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", $placeholders_list);

    $sql = "INSERT INTO bets ($columns) VALUES ($placeholders)";

    return db_insert($conn, $sql, $data);
}
