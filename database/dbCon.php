<?php 
    
// CORS Headers - Allow your frontend to talk to this backend
header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    //create db
    $pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS Item (
        id INTEGER PRIMARY KEY ,
        name TEXT NOT NULL,
        price INTEGER NOT NULL
    )");

    // 2. Bin Table (Storage Locations like 'Shelf A', 'Box 1')
    $pdo->exec("CREATE TABLE IF NOT EXISTS Bin (
        bin_id INTEGER PRIMARY KEY ,
        bin_name TEXT NOT NULL UNIQUE
    )");

    // 3. Stock Table (The "Bridge" - how many items are in which bin)
    $pdo->exec("CREATE TABLE IF NOT EXISTS Stock (
        stock_id INTEGER PRIMARY KEY ,
        item_id INTEGER NOT NULL,
        bin_id INTEGER NOT NULL,
        quantity INTEGER DEFAULT 0,
        FOREIGN KEY (item_id) REFERENCES Item(id) ON DELETE CASCADE,
        FOREIGN KEY (bin_id) REFERENCES Bin(bin_id) ON DELETE CASCADE
    )");

} catch (PDOException $e) {
    echo json_encode(["error" => "DB Connection failed: " . $e->getMessage()]);
    exit;
}

?>