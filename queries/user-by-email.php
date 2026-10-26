<?php

/**
 * @param mysqli $conn
 * @param string $email
 * @return array|false
 */
function getUserByEmail(mysqli $conn, string $email)
{
    $sql = "SELECT * FROM users WHERE email = ?";

    return fetchFromDbByParams($conn, $sql, [$email]);
}
