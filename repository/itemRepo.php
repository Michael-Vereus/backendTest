<?php 

class itemRepo{
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function test(){
        return ["msg" => "hay from itemRepo"];
    }
    public function fetch($ids){

        $arrIds = is_array($ids) ? $ids : [$ids];
        if (!empty($arrIds)){
            try {
                $placeholder = str_repeat('?, ', count($ids)-1). '?';

                $query = "SELECT * FROM Item WHERE id in ($placeholder)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrIds);
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $itemFetched = [];
                foreach ($result as $item){
                    $itemFetched[] = new itemEntity(
                        $item['id'],
                        $item['name'],
                        $item['price']
                    );
                }
                if (empty($itemFetched)) {
                    return ["msg" => "No items found matching the provided ID(s)."];
                }
                return $itemFetched;

            } catch (PDOException $e) {
                return ["msg" => "dbError check query"];
            }
        } else {
            return ["msg" => "empty id or not exist in db"];
        } 
    } // get Item based on Id's it can be either a single id or an arrays of id

    public function save(itemEntity $item){
        
        try {
            $query = "INSERT INTO Item 
                VALUES (:id,:name, :price)
                ON CONFLICT(id)
                DO UPDATE SET 
                    name = excluded.name,
                    price = excluded.price";
            $stmt = $this->pdo->prepare($query);
            $result =$stmt->execute([
                ':id' => $item->getId(),
                ':name' => $item->getName(),
                ':price' => $item->getPrice()
            ]); 

            return ["msg" => "item received", "item" => $item->getName()];
        } catch (PDOException $e) {
            return ["msg" => "err", "debug" => "dbError"];
        }        
    }
    
    public function deleteById($ids){

        
        $arrIds = is_array($ids) ? $ids : [$ids];

        if(!empty($arrIds)){
            try {
                $placeholder = str_repeat('?, ', count($ids)-1). '?';
                $query = "DELETE FROM Item 
                    WHERE id IN ($placeholder)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrIds);

                $deletedCount = $stmt->rowCount();
                $requestedCount = count($arrIds);

                if ($deletedCount === 0) {
                    return [
                        "status" => "error",
                        "msg" => "No items were found to delete."
                    ];
                }

                return [
                    "status" => "success",
                    "msg" => "Deleted $deletedCount out of $requestedCount requested items."
                ];
            }
            catch(PDOException $e){

            }
        } else {
            return ["msg" => "empty ID."];
        }
        
    }
}

?>