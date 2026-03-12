<?php

class BinRepo extends BaseRepo{
    private $pdo ;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function test(): bool{
        return true;
    }
    public function fetch($ids): array{
        $binFetched = [];

        $arrIDs = is_array($ids) ? $ids : [$ids];

        if(empty($arrIDs)){return $binFetched;}
        
        try {
            $placeholder = str_repeat('?, ', count($ids)-1). '?';

            $stmt = $this->pdo->prepare(
                "SELECT * FROM Bin WHERE binId in ($placeholder)"
            );
            $stmt->execute($arrIDs);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $bin) {
                $binFetched[] = new BinEntity(
                    $bin['binId'],
                    $bin['binName'],
                    $bin['Capacity']
                );
            }
            return $binFetched;

        } catch (PDOException $e) {
            return $binFetched;
        }
    } // fetch a single or batch item
    public function save(BinEntity $binItem): bool{
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Bin 
                VALUES (:binid, :binName, :Capacity)
                ON CONFLICT(binid)
                DO UPDATE SET 
                    binName = excluded.binName,
                    Capacity = excluded.Capacity"
            );
            $stmt->execute($binItem->getForDB());
            return $this->rowAffected($stmt);
        } catch (PDOException $e) {
            return false;
        }
    } // save upsert item
    public function deleteById($ids): bool{
        $arrIds = is_array($ids) ? $ids : [$ids] ;
        if (empty($arrIds)) {return false;}
        try {
            $placeholder = str_repeat('?, ', count($ids)-1). '?';
            
            $stmt = $this->pdo->prepare("DELETE FROM Bin
                WHERE binId IN ($placeholder)");
            $stmt->execute($arrIds);

            return $this->rowAffected($stmt);
        } catch (PDOException $e) {
            return false;
        }
    }
     // delete can be batch or single item
    public function checkId(string $id): bool{
        try {
            $query = $this->pdo->prepare(
                "SELECT COUNT(binid) FROM Bin
                WHERE binid = :binid"
            );

            $query->execute(['bind' => $id]);

            $count = $query->fetchColumn();

            return $count > 0;

        } catch (PDOException $e) {
            return false;
        }
    }
}

?>