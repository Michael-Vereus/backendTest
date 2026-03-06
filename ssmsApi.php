<?php 

declare(strict_types=1);

require_once 'masterCont.php';
require_once 'database/dbConn.php';
require_once 'service/itemService.php';
require_once 'service/binService.php';
require_once 'service/stockServ.php';
require_once 'repository/itemRepo.php';
require_once 'repository/binRepo.php';
require_once 'repository/stockRepo.php';
require_once 'model/itemEntity.php';
require_once 'model/binEntity.php';
require_once 'model/stockEntity.php';

header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$pdo = new dbConec("database/database.sqlite");
$incomingFile = file_get_contents('php://input');
$incomingData = json_decode($incomingFile, true);

if(!$incomingData){
    http_response_code(400);
    echo json_encode([
        "debug_error" => "err check JSON for typos",
        "rawDat" => $incomingData
    ]);
    exit;
}

$action = $incomingData["actionType"];
$whichServ = $incomingData["which"];

$itemServ = new ItemService($pdo->getPDO());
$binServ = new BinService($pdo->getPDO());
$stockServ = new StockServ($pdo->getPDO());
$mc = new MasterCont($pdo->getPDO());

switch ($whichServ) {

    case 'item':
        $result = $itemServ->run($incomingData,$action);
        echo json_encode($result);
        break;
    case 'stock':
        $result = $stockServ->run($incomingData,$action);
        echo json_encode($result);
        break;
    case 'bin':
        $result = $binServ->run($incomingData,$action);
        echo json_encode($result);
        break;
    case 'eepy': //ignore this shi
        echo json_encode([
            "msg" => "sleepy myself, ure tired ma niga"]); // also ignore
        break;
    case 'mc' :
        $result = $mc->run(); // master Cont
        echo json_encode($result);
        break;
    default:
        echo json_encode(["msg" => "Unknown Which?!"]);
        break;
}

?>