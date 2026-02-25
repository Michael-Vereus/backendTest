<?php 

class ItemService {
    private $pdo;

    public function __construct($db){
        $this->pdo = $db;
    }

    public function run($incoData, $action){
        switch ($action) {
            case 'test':
                echo json_encode(
                    "OK FROM run() 505."
                );
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
            default:
                # code...
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

            if (!is_array($ids)) { $ids = [$ids];};
            if(!empty($ids)){
                try{
                    $pld = str_repeat('?, ', count($ids)-1). '?';

                    $query = $this->pdo -> prepare("SELECT * FROM Item WHERE id in ($pld)");

                    $query -> execute($ids);
                    
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode([
                        "status" => "success",
                        "msg" =>  "Item fetched from db",
                        "items" => $result
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["status" => "failed", "msg" => "ID is empty"]);
            }
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
                        "status" => "error",
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
            echo json_encode(["msg" => "noice"]);
        } else {
            echo json_encode([
                "msg" => "OY THE REQUEST METHOD DUMBASS"
            ]);
        }
    }
}
?>