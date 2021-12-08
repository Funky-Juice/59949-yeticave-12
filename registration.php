<?php

require_once('db-methods.php');
require_once('validations.php');
require_once('queries/categories.php');
require_once('queries/user-by-email.php');
require_once('data/data.php');
require_once('helpers.php');
require_once('rules.php');

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $registration_validation_rules;
    $filteredData = filterDataByRules($_POST, $registration_validation_rules);

    $errors = validateForm($filteredData, $registration_validation_rules);

    if (empty($errors)) {
        $user_by_email = get_user_by_email($db_conn, $filteredData['email']);

        if (!is_array($user_by_email) && !$user_by_email) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        if (count($user_by_email)) {
            $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован';
        }
    }
}

show_screen('registration.php', 'Регистрация', 'errors', $errors, $categories_list);
