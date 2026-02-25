<?php 

require_once 'database/dbCon.php';
require_once 'service/itemService.php';

$incomingFile = file_get_contents('php://input');
$incomingData = json_decode($incomingFile, true);

if(!$incomingData){
    echo json_encode([
        "debug_error" => "err check JSON for typos",
        "rawDat" => $incomingData
    ]);
    exit;
}

$action = $incomingData["actionType"];
$whichServ = $incomingData["which"];

$itemServ = new ItemService($pdo);

switch ($whichServ) {

    case 'item':
        $itemServ->run($incomingData,$action);
        break;
    case 'stock':

        break;
    case 'bin':

        break;
    case 'eepy': //ignore this shi
        echo json_encode(["msg" => "sleepy myself, ure tired ma niga"]); // also ignore
        break;
    default:
        echo json_encode(["msg" => "Unknown Which?!"]);
        break;
}

?>