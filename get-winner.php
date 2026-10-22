<?php
require_once('mailer.php');

require_once('queries/set-lots-without-winner.php');
require_once('queries/expired-lot-ids.php');
require_once('queries/set-winners.php');
require_once('queries/get-winners-by-ids.php');

setExpiredLotsWithoutWinner($db_conn);

mysqli_query($db_conn, "START TRANSACTION");

$lotIds = getExpiredLotIds($db_conn);
$winnersUpdated = setWinners($db_conn);

if ($lotIds && $winnersUpdated) {
    mysqli_query($db_conn, "COMMIT");

    $winnersInfo = getWinnersInfoByIds($db_conn, $lotIds);

    if (!$winnersInfo && !is_array($winnersInfo)) {
        $error = getDbError($db_conn);
        showError($error);
        exit();
    }

    sendLettersToTheWinners($winnersInfo);
}
else {
    mysqli_query($db_conn, "ROLLBACK");
}
