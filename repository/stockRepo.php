<?php 

class StockRepo extends BaseRepo{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function test(): bool{
        return true;
    }
    public function fetch(array $requestedId): array{
        $arrIds = is_array($requestedId) ? $requestedId : [$requestedId] ;
        $stockFetched = [];
        if (empty($arrIds)){return [];}
        try {
            $placeholder = $this->createPlaceholder($requestedId);

            $query = $this->pdo->prepare(
                "SELECT * FROM Stock
                WHERE stockId in ($placeholder)");
            $query->execute($arrIds);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $stock){
                $stockFetched[] = new StockEntity(
                    $stock['stockId'],
                    $stock['binId'],
                    $stock['itemId'],
                    $stock['quantity']
                );
            }
            return $stockFetched;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function save(StockEntity $newStock): bool{
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
            return $this->rowAffected($query);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteById(array $ids): bool{
        $arrId = is_array($ids) ? $ids : [$ids];
        if (empty($arrId)){ http_response_code(400);}
        try {
            $placeholder = $this->createPlaceholder($arrId);

            $query = $this->pdo->prepare(
                "DELETE FROM Stock
                WHERE stockId IN ($placeholder)");

            $query->execute($arrId);
            
            return $this->rowAffected($query);
        } catch (PDOException $e){
            return false;
        }
    }

    public function checkById(string $id): bool{
        $arrId = is_array($id) ? $id : [$id];
        try {
            $placeholder = $this->createPlaceholder($arrId);
            $query = $this->pdo->prepare(
                "SELECT count(stockId) FROM Stock
                WHERE stockId in ($placeholder)"
            );
            $query->execute($arrId);
            return $query->fetchColumn() > 0;
        } catch(PDOException $e){
            return false;
        }
    }
    
}

?>