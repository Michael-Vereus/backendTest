<?php 

class BinEntity implements JsonSerializable{
    private $binId;
    private $binName;
    private $binCapacity;

    public function __construct($binId,$binName,$binCapacity) {
        $this->binId = $binId ?? $this->calculateId();
        $this->binName = $binName;
        $this->binCapacity = $binCapacity;
    }

    public function test(){
        return ["obj_msg" => "test from binEnt"];
    }
    private function calculateId(){
        $itemId = bin2hex(random_bytes(4));
        return $itemId;
    }
    public function jsonSerialize(){
        return [
            'binId' => $this->binId,
            'binName' => $this->binName,
            'binCapacity' => $this->binCapacity
        ];
    }
}

?>