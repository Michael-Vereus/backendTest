<?php 

class itemRepo extends BaseRepo{
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function test(): bool{
        return true;
    }
    public function fetch(array $requestedIds): array{ // param array of rere$requestedIds then return an array of objects (item entity)
        $itemFetched = [];

        if (empty($requestedIds)){ return $itemFetched;} //

        try {
            $placeholder = $this->createPlaceholder($requestedIds);

            $stmt = $this->pdo->prepare(
                "SELECT * FROM Item 
                WHERE itemId in ($placeholder)"
            );
            $stmt->execute($requestedIds);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $item){
                $itemFetched[] = new itemEntity(
                    $item['itemId'],
                    $item['itemName'],
                    $item['price']
                );
            }                
            return $itemFetched;

        } catch (PDOException $e) {
            return $itemFetched;
        }
    }

    public function save(itemEntity $item): bool{ // param get an item entity then return the result in array
        
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO Item 
                VALUES (:itemId, :itemName, :price)
                ON CONFLICT(itemId)
                DO UPDATE SET 
                    itemName = excluded.itemName,
                    price = excluded.price"
            );

            $stmt->execute($item->getForDb()); 

            return $this->rowAffected($stmt);
        } catch (PDOException $e) {
            return false;
        }        
    }

    public function checkId(string $id): bool{
        try {
            $query = $this->pdo->prepare(
                "SELECT COUNT(itemId) FROM Item
                WHERE itemId = :itemId"
            );

            $query->execute(['itemId' => $id]);

            $count = $query->fetchColumn();

            return $count > 0;

        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function deleteById($ids): bool{ //deleting a batch of item rows using the ids then return array
 
        $arrIds = is_array($ids) ? $ids : [$ids];

        if(empty($arrIds)){return false;};
        try {
            $placeholder = $this->createPlaceholder($arrIds);
            $stmt = $this->pdo->prepare("DELETE FROM Item 
                WHERE itemId IN ($placeholder)");
            $stmt->execute($arrIds);

            return $this->rowAffected($stmt);
        }
        catch(PDOException $e){
            return false;
        }

    }
}

?>