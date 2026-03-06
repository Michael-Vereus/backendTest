<?php 

class StockRepo{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function test(){
        return ["msg#3"=>"hey from stockRepo"];
    }
    public function fetch(array $requestedId): array{
        $arrIds = is_array($requestedId) ? $requestedId : [$requestedId] ;
        if (empty($arrIds)){http_response_code(400); return ["status"=>false];}
        try {
            $placeholder = str_repeat("?, ", count($arrIds)-1). " ?";
            $query = $this->pdo->prepare(
                "SELECT * FROM Stock
                WHERE stockId in ($placeholder)");
            $query->execute($arrIds);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            $stockFetched = [];
            foreach($result as $stock){
                $stockFetched = new StockEntity(
                    $stock['stockId'],
                    $stock['binId'],
                    $stock['itemId'],
                    $stock['quantity']
                );
            }
            if (empty($stockFetched)){
                http_response_code(200);
                return [
                    "status"=>true,
                    "msg"=>"Stock with that ID doesn't exist !"
                ];
            }
            http_response_code(200);
            return ["status"=>true, "result"=>$stockFetched];
        } catch (PDOException $e) {
            return ["status"=>false];
        }
    }

    public function save(StockEntity $newStock):array{
        try {

            $query = $this->pdo->prepare(
                "INSERT INTO Stock
                VALUES (:stockId, :itemId, :binid, :quantity)
                ON CONFLICT(stockId)
                DO UPDATE SET 
                    itemId = excluded.itemId,
                    binid = excluded.binid,
                    quantity = excluded.quantity"
            );

            $query->execute($newStock->getForDB());
            http_response_code(201);
            return ["msg"=>$newStock->getForDB()];
        } catch (PDOException $e) {
            http_response_code(500);
            return [
                "msg"=>"dbError",
                "error_type" => get_class($e),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ];
        }
    }

    public function deleteById(array $ids){
        $arrId = is_array($ids) ? $ids : [$ids];
        if (empty($arrId)){ http_response_code(400);}
        try {
            $placeholder = str_repeat("?, ", count($arrId)-1) . " ?";

            $query = $this->pdo->prepare(
                "DELETE FROM Stock
                WHERE stockId IN ($placeholder)");

            $query->execute($arrId);
            
            $result = $query->rowCount();
            http_response_code(200);
            if ($result === 0) {
                return [
                    "status"=>true,
                    "msg"=>"Stock doesn't exist to be deleted"
                ];
            };
            $requested = count($arrId);
            return [
                "status"=>true,
                "msg"=> $result." out of ".$requested ." Stock has been deleted"
            ];
        } catch (PDOException $e){
            http_response_code(500);
            return [
                "status"=>false, 
                "msg"=>"Failed to process, dbError"
            ];
        }
    }
    
}

?>