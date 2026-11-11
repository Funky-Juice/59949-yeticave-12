<?php

$db_conn = require_once('init.php');

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showDbError($error);
    exit();
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'consent.php',
    'title' => 'Согласие на обработку персональных данных',
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
