<?php 

class BinService extends BaseService{
    private $binRepo;

    public function __construct($pdo) {
        $this->binRepo = new BinRepo($pdo);
    }

    public function run($incomingData,$action){
        
        switch ($action) {
            case 'test':
                return $this->test();
                break;
            case 'lsBin':
                return $this->getBin($incomingData);
                break;
            case 'touchBin':
                return $this->addBin($incomingData);
                break;
            case 'nanoBin' : 
                return $this->updateBin($incomingData);
                break;
            case 'rmBin' :
                return $this->removeBin($incomingData);
                break;
            default:
                return [
                    "status"=>"err",
                    "msg" => "Unknown action type"
                ];
                break;
        }
    }
    private function test(): array{
        return [$this->binRepo->test()];
    }
    public function getBin($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $ids = $incomingData['binId'] ?? [];
            $result = $this->binRepo->fetch($ids);
            $check = $this->isEmptyArray($result);
            return $this->getReturnArray(
                $check,
                $this->isTrue($check),
                $result
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    public function addBin($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $newBin = $this->createBin($incomingData);
            $result = $this->binRepo->save($newBin);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    public function removeBin($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $ids = $incomingData['binId'] ?? [];
            $result = $this->binRepo->deleteById($ids);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    public function updateBin($incomingData): array {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $check = $this->binRepo->checkId($incomingData['binId']);
            if($check === false) {
                return $this->getReturnArray(
                    $check,
                    "ID Not Exists"
                );
            }
            $newBin = $this->createBin($incomingData);
            $result = $this->binRepo->save($newBin);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    private function createBin(array $incomingData):BinEntity {
        return new BinEntity(
            $incomingData['binId'],
            $incomingData['binName'],
            $incomingData['Capacity']
        );
    }
}

?>