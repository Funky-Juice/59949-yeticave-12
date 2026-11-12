<?php

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

/**
 * Пишет подробности сбоя в лог и отдаёт посетителю общую страницу со статусом 500.
 * В ответ не попадают текст исключения, SQL и аргументы вызовов.
 */
function appRenderServerError(string $details): void
{
    static $handling = false;

    if ($handling) {
        return;
    }

    $handling = true;

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        http_response_code(500);
    }

    if (function_exists('showDbError')) {
        showDbError($details);
        exit();
    }

    error_log($details);
    echo 'Ошибка сервера. Попробуйте обновить страницу позже.';
    exit();
}

/**
 * @param Throwable $exception
 * @return void
 */
function appHandleException(Throwable $exception): void
{
    appRenderServerError(sprintf(
        'Uncaught %s: %s in %s:%d%s%s',
        $exception::class,
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        PHP_EOL,
        $exception->getTraceAsString()
    ));
}

/**
 * @param int $severity
 * @param string $message
 * @param string $file
 * @param int $line
 * @return bool
 */
function appHandleError(int $severity, string $message, string $file, int $line): bool
{
    if (!(error_reporting() & $severity)) {
        return false;
    }

    appRenderServerError(sprintf(
        'PHP error %d: %s in %s on line %d',
        $severity,
        $message,
        $file,
        $line
    ));

    return true;
}

/**
 * @return void
 */
function appHandleShutdown(): void
{
    $error = error_get_last();

    if ($error === null) {
        return;
    }

    $fatal = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR;

    if (($error['type'] & $fatal) === 0) {
        return;
    }

    appRenderServerError(sprintf(
        'PHP fatal %d: %s in %s on line %d',
        $error['type'],
        $error['message'],
        $error['file'],
        $error['line']
    ));
}

set_exception_handler('appHandleException');
set_error_handler('appHandleError');
register_shutdown_function('appHandleShutdown');

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
