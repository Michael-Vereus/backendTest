<?php 
class ItemService {
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
            $ids = $incomingData['id'] ?? [];
            
            $result = $this->itemRepo->fetch($ids);
            return $result;
        } else {
            return (["msg"=> "Oi The Request Method IS WRONG!"] );
        }
    }
    public function addItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $newItem = new itemEntity(
                $incomingData['id'],
                $incomingData['name'],
                $incomingData['price']
            );

            $result = $this->itemRepo->save($newItem);

            return $result;
        } else{
            return (["msg" => "WRONG METHOD DUMBASS"]);
        }
    }
    public function removeItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            
            $ids = $incomingData['id'] ?? null;

            $result = $this->itemRepo->deleteById($ids);
            return $result;
            
        } else {
            return (["msg" => "OY THE REQUEST METHOD DUMBASS"]);
        }
    }
    public function updateItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            
            $newItem = new itemEntity($incomingData['id'],$incomingData['name'],$incomingData['price']);
            $result = $this->itemRepo->save($newItem);
            return $result;
        } else {
            return (["msg" => "Check the Request Method Dumbass"]);
        }
    }
}
?>