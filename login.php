<?php

require_once('db-methods.php');
require_once('queries/categories.php');
require_once('helpers.php');

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

show_screen('login.php', 'Аутентификация', 'errors', $errors, $categories_list);
