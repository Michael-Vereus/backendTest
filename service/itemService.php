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
                return ["status"=>$this->itemRepo->checkId("2762cc1b")];
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
                return ["msg" => "Unknown Action ! "];
                break;
        }
    }
    public function test(){
        $result = $this->itemRepo->test();
        return (["msg" => "hey from itemServ API", "msg#2" =>$result]  );
    }

    public function getItems($incomingData){
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
    public function addItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $newItem = $this->createItem($incomingData);

            $result = $this->itemRepo->save($newItem);
            return $this->getReturnArray(
                $result,
                $this->isTrue($result),
            );
        } else{
            return $this->errorMethodHandler();
        }
    }
    public function removeItems($incomingData){
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
            $newItem = $this->createItem($incomingData);
            
            $result = $this->itemRepo->save($newItem);

            return $this->getReturnArray(
                $result,
                $this->isTrue($result)
            );
        } else {
            return $this->errorMethodHandler();
        }
    }
    private function createItem(array $incomingData): itemEntity{
        return new itemEntity(
            $incomingData['itemId'],
            $incomingData['itemName'],
            $incomingData['price']
        );
    }
}
?>