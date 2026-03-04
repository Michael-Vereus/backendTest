<?php 

class dbConec{
    private PDO $pdo;

    public function __construct(string $dbPath) {
        try {
            $this->pdo = new PDO('sqlite:'. $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->pdo->exec("PRAGMA foreign_keys = ON;");
            $this->initTables();
        } catch(PDOException $e){
            header('Content-Type: application/json');
            echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
            exit;
        }
    }

    public function getPDO() : PDO {
        return $this->pdo;
    }

    private function initTables(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS Item (
            itemId VARCHAR(10) PRIMARY KEY ,
            itemName TEXT NOT NULL,
            price INTEGER NOT NULL
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS Bin (
            binid VARCHAR(10) PRIMARY KEY ,
            binName TEXT NOT NULL, 
            Capacity INTEGER 
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS Stock (
            stockId VARCHAR(10) PRIMARY KEY,
            itemId VARCHAR(10) NOT NULL,
            binId VARCHAR(10) NOT NULL,
            quantity INTEGER DEFAULT 0,
            FOREIGN KEY (itemId) REFERENCES Item(itemId) ON DELETE CASCADE,
            FOREIGN KEY (binId) REFERENCES Bin(binId) ON DELETE CASCADE
        )");

    }
}

?>