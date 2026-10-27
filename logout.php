<?php

require_once('helpers.php');

startAppSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    showError('Доступ запрещен 405');
    exit();
}

verifyCsrf();
destroyAppSession();

header('Location: index.php');
