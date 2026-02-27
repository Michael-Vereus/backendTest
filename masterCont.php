<?php

class MasterCont{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function test(){
        return ["Test Master Control."];
    }
    public function run(){
        $result = $this->exec();
        return ["status" => $result];
    }
    private function exec(){

        try {
            $stmt = $this->pdo->prepare(
            "ALTER TABLE Stock 
            RENAME COLUMN bin_id TO binId");

            $stmt->execute();

            return ["msg" => "query completed"];
        } catch (PDOException $e) {
            return ["msg" => "db Error nor syntax"];
        }
    }
}

?>