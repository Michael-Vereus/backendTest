<?php 

if($_SERVER['REQUEST_METHOD'] === 'PUT'){

    $itemID = $incomingData['id'] ;
    $itemName = $incomingData['name'] ;
    $itemPrice = $incomingData['price'];

    if($itemID){

        try {
            $query = $pdo->prepare(
                "UPDATE Item 
                SET name = :name,
                    price = :price 
                WHERE id = :id");

            $query->execute([
                ":name" => $itemName,
                ":price" => $itemPrice,
                ":id" => $itemID
            ]);
            echo json_encode([
                "status" => "success",
                "msg" => "Updated an Item from db",
                "Item" => $itemName
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error",
                "msg" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "Empty ID!"
        ]);
    }
} else {
    json_encode(["error" => "Wrong Request Method"]);
}
?>