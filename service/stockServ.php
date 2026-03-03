<?php 

class StockServ{
    private StockRepo $stockRepo;

    public function __construct(PDO $PDO){
        $this->stockRepo = new StockRepo($PDO);
    }
    public function run(array $incomingData,string $action){
        switch($action){
            case 'test':
                return $this->test();
                break;
            case 'lsStock':
                return $this->getStock($incomingData);
                break;
            case 'touchStock':
                return $this->addStock($incomingData);
                break;
            default:
                return ["msg"=>"Unknown Action Type"];
                break;
        }
    }
    public function test(){
        return ["msg"=>"hey from stockServ","msg#2"=>$this->stockRepo->test()];
    }
    public function getStock(array $incomingData){
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $ids = $incomingData['stockId'] ?? [];
            return ["status"=>$this->stockRepo->fetch($ids)];
        } else {
            return ["wrong method dumbass"];
        }
    }
    public function addStock(array $incomingData):array{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $listStock = [];
            $newStock = new StockEntity(
                $incomingData['stockId'],
                $incomingData['binId'],
                $incomingData['itemId'],
                $incomingData['quantity']
            );

            $result = $this->stockRepo->save($newStock);
            return $result;
        } else {
            return ["msg"=>"wrong"];
        }
    }
}

?>