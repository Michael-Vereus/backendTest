<?php 

class ItemService {
    private $pdo;

    public function __construct($db){
        $this->pdo = $db;
    }

    public function run($incoData, $action){
        switch ($action) {
            case 'test':
                $this->test();
                break;
            case 'touchItem':
                $this->addItems($incoData);
                break;
            case 'lsItem':
                $this->getItems($incoData);
                break;
            case 'rmItem':
                $this->removeItems($incoData);
                break;
            case 'nanoItem' :
                $this->updateItems($incoData);
                break;
            default:
                echo json_encode(["msg" => "Unknown Action ! "]);
                break;
        }
    }
    public function test(){
        if ($this->pdo){
            echo json_encode(["msg" => "hey from itemServ API"]);
        }
    }

    public function getItems($incomingData){
        if ($_SERVER['REQUEST_METHOD'] === "GET"){
            $ids = $incomingData['id'] ?? [];

            if (!is_array($ids)) { $arrIds = [$ids];};
            if($arrIds !==null){
                try{
                    $pld = str_repeat('?, ', count($arrIds)-1). '?';

                    $query = "SELECT * FROM Item WHERE id in ($pld)";

                    $stmt = $this->pdo -> prepare($query);

                    $stmt -> execute($arrIds);
                    
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode([
                        "status" => "success",
                        "msg" =>  "Item fetched from db",
                        "items" => $result
                    ]);
                } catch (PDOException $e) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["status" => "failed", "msg" => "ID is empty"]);
            }
        } else {
            echo json_encode(["msg"=> "Oi The Request Method IS WRONG!"] );
        }
    }
    public function addItems($incomingData){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

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
            }
        } else{
            echo json_encode(["msg" => "WRONG METHOD DUMBASS"]);
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