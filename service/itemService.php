<?php 
class ItemService extends BaseService{
    private $itemRepo;

    public function __construct($pdo){
        $this->itemRepo = new itemRepo($pdo);
    }

    public function run($incoData, $action){
        switch ($action) {
            case 'test':
                // return ["ok"];
                return $this->test();
                break;
            case 'touchItem':
                return $this->addItems($incoData);
                break;
            case 'lsItem':
                return $this->getItems($incoData);
                break;
            case 'rmItem':
                return $this->removeItems($incoData);
                break;
            case 'nanoItem' :
                return $this->updateItems($incoData);
                break;
            default:
                return $this->returnUnknownAction($action);
                break;
        }
    }
    public function test(){
        $result = $this->itemRepo->test();
        return $this->getReturnArray(
            $result,
            $this->isTrue($result)
        );
    }

    public function getItems($incomingData): array{
        if ($_SERVER['REQUEST_METHOD'] === "GET"){
            $ids = $incomingData['itemId'] ?? []; // get the id from array
            //check if its an array or not.
            $arrIds = is_array($ids) ? $ids : [$ids]; 

            $result = $this->itemRepo->fetch($arrIds);
            $status = $this->isEmptyArray($result);

            return $this->getReturnArray(
                $status,
                $this->isTrue($status),
                $result
            );
        } else {
            return $this->errorMethodHandler();
        }
    }  
    public function addItems($incomingData): array{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $newItem = $this->createObj($incomingData);

            $result = $this->itemRepo->save($newItem);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result),
            );
        } else{
            return $this->errorMethodHandler();
        }
    }
    public function removeItems($incomingData): array{
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            
            $ids = $incomingData['itemId'] ?? null;
            
            $result = $this->itemRepo->deleteById($ids);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    public function updateItems($incomingData): array{
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $check = $this->itemRepo->checkId($incomingData['itemId']);
            if($check === false) {
                return $this->getReturnArray(
                    $check,
                    "ID Not Exists"
                );
            }
            $newItem = $this->createObj($incomingData);
            
            $result = $this->itemRepo->save($newItem);

            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    // to create item.
    private function createObj(array $incomingData): itemEntity{
        return new itemEntity(
            $incomingData['itemId'],
            $incomingData['itemName'],
            $incomingData['price']
        );
    }
}
?>