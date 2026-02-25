<?php
if($_SERVER['REQUEST_METHOD'] === "POST"){
    try {
        echo "Starting seeding... \n";

        // 1. Seed Bins (Locations)
        $bins = ['Shelf A', 'Shelf B', 'Refrigerator', 'Warehouse Main'];
        $binStmt = $pdo->prepare("INSERT OR IGNORE INTO Bin (bin_name) VALUES (?)");
        
        foreach ($bins as $bin) {
            $binStmt->execute([$bin]);
        }
        echo "Bins seeded! \n";

        // 2. Seed Items
        $items = [
            ['name' => 'Pop Mie Ayam', 'price' => 4500],
            ['name' => 'Indomie Goreng', 'price' => 3500],
            ['name' => 'Aqua 600ml', 'price' => 3000]
        ];
        
        $itemStmt = $pdo->prepare("INSERT OR IGNORE INTO Item (name, price) VALUES (:name, :price)");
        foreach ($items as $item) {
            $itemStmt->execute($item);
        }
        echo "Items seeded! \n";

        // 3. Seed some initial Stock (Link Item 1 to Bin 1)
        // We assume ID 1 exists because we just cleared the DB.
        $pdo->exec("INSERT OR IGNORE INTO Stock (item_id, bin_id, quantity) VALUES (1, 1, 50)");
        
        echo json_encode(["status" => "success", "message" => "Database seeded successfully!"]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
