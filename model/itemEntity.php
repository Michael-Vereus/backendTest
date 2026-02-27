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

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getPrice(){
        return $this->price;
    }
    public function jsonSerialize(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }

}

?>