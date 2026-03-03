<?php

class BinRepo{
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function test(){
        $testBin = new BinEntity(1,2,3);
        return ["msg#2" => "Hey From BinRepo", "msg#3" => $testBin->test()];
    }
    public function fetch($ids): array{
        $arrIDs = is_array($ids) ? $ids : [$ids];
        if(!empty($arrIDs)){
            $placeholder = str_repeat('?, ', count($ids)-1). '?';
            
            try {
                $query = "SELECT * FROM Bin WHERE binId in ($placeholder)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrIDs);

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $binFetched = [];
                foreach ($result as $bin) {
                    $binFetched[] = new BinEntity(
                        $bin['binId'],
                        $bin['binName'],
                        $bin['Capacity']
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
    } // fetch a single or batch item
    public function save(BinEntity $binItem){
        try {
            $query = "INSERT INTO Bin 
                VALUES (:binId, :binName, :Capacity)
                ON CONFLICT(binId)
                DO UPDATE SET 
                    binName = excluded.binName,
                    Capacity = excluded.Capacity";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($binItem->getForDB());
            $result = ["status"=>"success","msg"=>"OK 505 SAVED TO DB"] ;
            return $result;
        } catch (PDOException $e) {
            return [
                "status"=>"err",
                "msg"=>"dbError",
                "error_type" => get_class($e),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ];
        }
    } // save upsert item
    public function deleteById($ids){
        $arrIds = is_array($ids) ? $ids : [$ids] ;
        if (empty($arrIds)) {return ["status"=>"err", "msg"=>"No ID received"];}
        try {
            //placeholder for query
            $placeholder = str_repeat('?, ', count($ids)-1). '?';
            $query = "DELETE FROM Bin
                WHERE binId IN ($placeholder)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($arrIds);
            $result = [
                "status"=>"success",
                "msg"=>"OK 505"
            ];
            //check if there are any row deleted
            if ($stmt->rowCount() === 0){return ["status"=>"success","msg"=>"No BinId Match"];};

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
    } // delete can be batch or single item
}

?>