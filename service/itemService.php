<?php 
class ItemService extends BaseService{
    private $itemRepo;

    public function __construct($pdo){
        $this->itemRepo = new itemRepo($pdo);
    }

    public function run($incoData, $action){
        switch ($action) {
            case 'test':
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
            return $result;
        } else {
            http_response_code(405);
            return (["msg"=> "Wrong Request Method"] );
        }
    }
    public function addItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $newItem = $this->createItem($incomingData);

            $result = $this->itemRepo->save($newItem);
            return $result;
        } else{
            http_response_code(405);
            return (["msg"=> "Wrong Request Method"] );
        }
    }
    public function removeItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            
            $ids = $incomingData['itemId'] ?? null;

            $result = $this->itemRepo->deleteById($ids);
            return $result;
        } else {
            http_response_code(405);
            return (["msg"=> "Wrong Request Method"] );
        }
    }
    public function updateItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            
            $newItem = $this->createItem($incomingData);

            $result = $this->itemRepo->save($newItem);
            return $result;
        } else {
            http_response_code(405);
            return (["msg"=> "Wrong Request Method"] );
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