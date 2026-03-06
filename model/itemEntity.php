<?php 

class itemEntity implements JsonSerializable {
    private $id;
    private $name;
    private $price;

    public function __construct($id,$name, $price) {
        $this->id = $id ?? $this->calculateId();
        $this->name = $name;
        $this->price = $price;
    }

    private function calculateId(){
        $itemId = bin2hex(random_bytes(4));
        return $itemId;
    }
    public function jsonSerialize(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }
    public function getForDb(){
        return [
            "itemId"=>$this->id,
            "itemName"=>$this->name,
            "price"=>$this->price
        ];
    }
    

}

?>