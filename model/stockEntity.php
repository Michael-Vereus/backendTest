<?php

class StockEntity implements JsonSerializable{
    private string $stockId;
    private string $binId;
    private string $itemId;
    private int $quantity;

    public function __construct(?string $stockId, string $binId, string $itemId,int $quantity) {
        $this->stockId = $stockId ?? $this->genId();
        $this->binId = $binId;
        $this->itemId = $itemId;
        $this->quantity = $quantity;
    }

    private function genId(): string{
        return bin2hex(random_bytes(5));
    }

    public function jsonSerialize(){
        return [
            "stockId"=>$this->stockId,
            "binid"=>$this->binId,
            "itemId"=>$this->itemId,
            "quantity"=>$this->quantity
        ];
    }
    public function getForDB(): array{
        return [
            "stockId"=>$this->stockId,
            "itemId"=>$this->itemId,
            "binid"=>$this->binId,
            "quantity"=>$this->quantity
        ];
    }
}

?>