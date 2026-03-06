<?php 

class itemRepo{
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function test(){
        
        return ["msg" => "hay from itemRepo"];
    }
    public function fetch(array $requestedIds): array{ // param array of rere$requestedIds then return an array of objects (item entity)

        $arrIds = is_array($requestedIds) ? $requestedIds : [$requestedIds]; //check if its an array or not.

        if (empty($arrIds)){ return ["msg" => "ID's are empty"];} //

        try {
            $placeholder = str_repeat('?, ', count($requestedIds)-1). '?';

            $stmt = $this->pdo->prepare(
                "SELECT * FROM Item 
                WHERE itemId in ($placeholder)"
            );
            $stmt->execute($arrIds);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $itemFetched = [];
            foreach ($result as $item){
                $itemFetched[] = new itemEntity(
                    $item['itemId'],
                    $item['itemName'],
                    $item['price']
                );
            }
            $msg = "Item fetched!";
            if (empty($itemFetched)) {$msg = "No such ID EXIST!";}
                
            return [
                "status"=>"success",
                "msg"=>$msg,
                "item" => $itemFetched];

        } catch (PDOException $e) {
            return ["msg" => "dbError check query"];
        }
    }

    public function save(itemEntity $item): array{ // param get an item entity then return the result in array
        
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

            return [
                "status"=>"success",
                "msg" => "item received"
            ];
        } catch (PDOException $e) {
            return [
                "status"=>"err",
                "msg"=>"dbErr check query"
            ];
        }        
    }
    
    public function deleteById($ids): array{ //deleting a batch of item rows using the ids then return array

        
        $arrIds = is_array($ids) ? $ids : [$ids];

        if(empty($arrIds)){return ["msg"=>"ID is empty!"];};
        try {
            $placeholder = str_repeat('?, ', count($ids)-1). '?';
            $query = "DELETE FROM Item 
                WHERE id IN ($placeholder)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($arrIds);

            $deletedCount = $stmt->rowCount();
            $requestedCount = count($arrIds);
            
            $msg = "Deleted $deletedCount out of $requestedCount requested items.";
            if ($deletedCount === 0) {
                $msg = "No such ID Exist";
            }

            return [
                "status" => "success",
                "msg" => $msg
            ];
        }
        catch(PDOException $e){
            return ["msg"=>"dbError check query"];
        }
        
    }
}

?>