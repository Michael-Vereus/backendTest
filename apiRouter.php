<?php

class ApiRouter{
    private ItemService $itemServ;
    private BinService $binServ;
    private StockServ $stockServ;
    private MasterCont $mc;

    public function __construct(PDO $pdo) { 
        $this->itemServ = new ItemService($pdo);
        $this->binServ = new BinService($pdo);
        $this->stockServ = new StockServ($pdo);
        $this->mc = new MasterCont($pdo);
    }

    public function handleResponse($incomingData): array{   
        switch ($incomingData["which"]) {
            case 'test':
                return $this->test();
                break;
            case 'item' : 
                return $this->itemServ->run(
                    $incomingData, 
                    $incomingData["actionType"]
                );
            case 'stock' : 
            return $this->itemServ->run(
                    $incomingData, 
                    $incomingData["actionType"]
                );
            case 'bin' : 
                return $this->binServ->run(
                    $incomingData, 
                    $incomingData["actionType"]
                );
            default:
                return ["Unknown Service"];
                break;
        }
    }

    private function test(): array{
        return ["test API Router"];
    }
}

?>