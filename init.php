<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('validations.php');
require_once('queries/categories.php');

startAppSession();

$db_conn = getDbConnect();

if (!$db_conn) {
    showDbError(getDbConnectionError() ?? 'Не удалось подключиться к базе');
    exit();
}

return $db_conn;
