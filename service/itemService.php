<?php 
class ItemService {
    private $pdo;
    private $itemRepo;

    public function __construct($db){
        $this->pdo = $db;
        $this->itemRepo = new itemRepo($this->pdo);
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
        if ($this->pdo){
            $result = $this->itemRepo->test();
            return (["msg" => "hey from itemServ API", "msg#2" =>$result]  );
        }
    }

    public function getItems($incomingData){
        if ($_SERVER['REQUEST_METHOD'] === "GET"){
            $ids = $incomingData['id'] ?? [];
            
            $result = $this->itemRepo->fetch($ids);
            return ["msg" => "success", "result" => $result];
        } else {
            return (["msg"=> "Oi The Request Method IS WRONG!"] );
        }
    }
    public function addItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $result = $this->itemRepo->save();

            return $result;
            
            /*
            $itemName = $incomingData['name'];
            $itemPrice = $incomingData['price'];

            $itemId = (int)(time() . random_int(100, 999));

            if($itemName !== null){
                try {
                    $query = "INSERT INTO Item (id, name, price) VALUES (:id, :name, :price)";
                    
                    $stmt = $this->pdo->prepare($query);
                    $stmt -> execute([
                        ':id' => $itemId,
                        ':name' => $itemName,
                        ':price' => $itemPrice
                    ]);

                    echo json_encode([
                        "status" => "success",
                        "msg" => "Successfully added Items",
                        "name_item" => $itemName
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
                }
            } */
        } else{
            return (["msg" => "WRONG METHOD DUMBASS"]);
        }
    }
    public function removeItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            $itemID = $incomingData['id'] ?? null;
            
            
            if (!is_array($itemID)) { $arrIds = [$itemID];};
            if ($arrIds !== null){
                try {

                    $query = "DELETE FROM Item WHERE :id";

                    $stmt = $this->pdo->prepare($query);
                    $stmt -> execute($itemID);

                    echo json_encode([
                        "status" => "success",
                        "msg" => "Item Removed from DB"
                    ]);

                } catch (PDOException $e) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => "db error"
                    ]);
                }
            } else {
                json_encode(["msg" => "id is null"]);
            }
        } else {
            echo json_encode([
                "msg" => "OY THE REQUEST METHOD DUMBASS"
            ]);
        }
    }
    public function updateItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            
            $itemID = $incomingData['id'] ;
            $itemName = $incomingData['name'] ;
            $itemPrice = $incomingData['price'];

            if($itemID){

                try {
                    $query = $this->pdo->prepare(
                        "UPDATE Item 
                        SET name = :name,
                            price = :price 
                        WHERE id = :id");

                    $query->execute([
                        ":name" => $itemName,
                        ":price" => $itemPrice,
                        ":id" => $itemID
                    ]);
                    echo json_encode([
                        "status" => "success",
                        "msg" => "Updated an Item from db",
                        "Item" => $itemName
                    ]);
                } catch (PDOException $e) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Empty ID!"
                ]);
            }
        } else {
            echo json_encode(["msg" => "Check the Request Method Dumbass"]);
        }
    }
}
?>