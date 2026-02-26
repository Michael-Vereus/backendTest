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
        //func here
        $arrIds = is_array($ids) ? $ids : [$ids];
        if (!empty($arrIds)){
            try {
                $placeholder = str_repeat('?, ', count($ids)-1). '?';

                $query = "SELECT * FROM Item WHERE id in ($placeholder)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrIds);
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (empty($result)) {
                    return ["msg" => "No items found matching the provided ID(s)."];
                }
                return $result;

            } catch (PDOException $e) {
                return ["msg" => "dbError check query"];
            }
        } else {
            return ["msg" => "empty id."];
        } 
    }

    public function save(){
        return ["msg" => "hey from save"];
    }
}

?>