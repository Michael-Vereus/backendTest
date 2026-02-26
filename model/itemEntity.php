<?php 

class itemEntity {
    private $id;
    private $name;
    private $price;

    public function __construct($name, $price) {
        $this->id = $this->getId();
        $this->name = $name;
        $this->price = $price;
    }

    private function calculateId(){
        $itemId = bin2hex(random_bytes(4));
        return $itemId;
    }
    public function getId(){
        return $this->calculateId();
    }

}

?>