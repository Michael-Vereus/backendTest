<?php

class BinRepo{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function test(){
        $testBin = new BinEntity(1,2,3);
        return ["msg#2" => "Hey From BinRepo", "msg#3" => $testBin->test()];
    }
    public function fetch($ids){
        $arrIDs = is_array($ids) ? $ids : [$ids];
        if(!empty($arrIDs)){
            $placeholder = str_repeat('?, ', count($ids)-1). '?';
            
            try {
                $query = "SELECT * FROM Bin WHERE binId in ($placeholder)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrIDs);

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $binFetched = [];
                foreach ($binFetched as $result => $bin) {
                    $binFetched[] = new BinEntity(
                        $bin['binId'],
                        $bin['binName'],
                        $bin['binCapacity']
                    );
                }
                if (empty($binFetched)) {
                    return ["msg" => "No bins found matching the provided ID(s)."];
                }
                return [
                    "msg"=>"success",
                    "result"=>$binFetched
                ];
            } catch (PDOException $e) {
                return ["msg"=>"dbError"];
            }
        } else {
            return ["msg"=>"No ID received"];
        }
    } // fetch item 
    public function save(BinEntity $binItem){
        try {
            $result = ["test Save func"] ;
            return $result;
        } catch (PDOException $e) {
            return [
                "msg"=>"dbError",
                "error_type" => get_class($e),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ]; 
        }
    }
}

?>