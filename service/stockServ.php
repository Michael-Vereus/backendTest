<?php 

class StockServ extends BaseService{
    private StockRepo $stockRepo;

    public function __construct(PDO $PDO){
        $this->stockRepo = new StockRepo($PDO);
    }
    public function run(array $incomingData,string $action){
        switch($action){
            case 'test':
                return $this->test();
                break;
            case 'lsStock':
                return $this->getStock($incomingData);
                break;
            case 'touchStock':
                return $this->addStock($incomingData);
                break;
            case 'rmStock' : 
                return $this->removeStock($incomingData);
            case 'nanoStock' : 
                return $this->updateStock($incomingData);
            default:
                return ["msg"=>"Unknown Action Type"];
                break;
        }
    }
    public function test(){
        $result = $this->stockRepo->test();
        return $this->getReturnArray(
            $result,
            $this->isTrue($result)
        );
    }
    public function getStock(array $incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $ids = $incomingData['stockId'] ?? [];
            $result = $this->stockRepo->fetch($ids);
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
    public function addStock(array $incomingData):array{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $listStock = [];
            $newStock = $this->createObj($incomingData);

            $result = $this->stockRepo->save($newStock);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    public function removeStock($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $ids = $incomingData['stockId'] ?? [];
            $result = $this->stockRepo->deleteById($ids);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return [];
        }
    }
    public function updateStock($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $result = false;
            $exist = $this->stockRepo->checkById($incomingData['stockId']);
            if(!$exist){
                return ['ID not exist'];
            } else {
                $result = $this->stockRepo->save(
                    $this->createObj($incomingData)
                );
                return $this->getReturnArray(
                    $result,
                    $this->isTrue($result)
                );
            }
            
        } else {
            return $this->errorMethodHandler();
        }
    }

    private function createObj(array $incomingData): StockEntity{
        return new StockEntity(
            $incomingData['stockId'],   
            $incomingData['binId'],
            $incomingData['itemId'],
            $incomingData['quantity']
        );
    }
}

?>