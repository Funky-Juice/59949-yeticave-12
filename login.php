<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/user-by-email.php');
require_once('queries/login-attempts.php');

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showDbError($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $filteredData = filterDataByRules($_POST, $rules['login'] ?? []);

    $errors = validateForm($filteredData, $rules['login'] ?? []);

    if (empty($errors)) {
        $ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
        $email = (string) ($filteredData['email'] ?? '');
        $limit = checkLoginAttempts($db_conn, $ip, $email);

        if ($limit === false) {
            showDbError(getDbError($db_conn));
            exit();
        }

        if ($limit['blocked']) {
            $minutes = (int) $limit['retry_after_minutes'];
            http_response_code(429);
            header('Retry-After: ' . ($minutes * 60));
            $errors['form'] = 'Слишком много попыток, повторите через '
                . $minutes . ' '
                . getNounPluralForm($minutes, 'минуту', 'минуты', 'минут');
        } else {
            $user = getUserByEmail($db_conn, $email);

            if (!is_array($user) && !$user) {
                showDbError(getDbError($db_conn));
                exit();
            }

            if (empty($user)) {
                $errors['email'] = 'Пользователь с такой почтой не найден';
                recordLoginAttempt($db_conn, $ip, $email);
            } elseif (password_verify($filteredData['password'] ?? '', $user[0]['password'] ?? '')) {
                resetLoginAttempts($db_conn, $ip, $email);
                regenerateAppSession();
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit();
            } else {
                $errors['password'] = 'Неверный пароль';
                recordLoginAttempt($db_conn, $ip, $email);
            }
        }
    }
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'login.php',
    'title' => 'Аутентификация',
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
