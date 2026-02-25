<?php 

if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    $itemID = $incomingData['id'] ?? null;

    if($itemID){
        try {
            $query = $pdo->prepare("DELETE FROM Item WHERE(:id)");

            $query->execute([":id"=> $itemID]);

            if ($query->rowCount() > 0){
                echo json_encode([
                "status" => "success",
                "msg" => "Removed an Item from db",
                "itemID" => $itemID
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Item ID $itemID not found in database"
                ]);
            }
            
        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "No ID provided"]);
    }
}
?>