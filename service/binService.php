<?php 

class BinService{
    private $pdo;
    private $binRepo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->binRepo = new BinRepo();
    }

    public function run($incomingData,$action){
        
        switch ($action) {
            case 'test':
                return $this->test();
                break;
            
            default:
                # code...
                break;
        }
    }
    private function test(){
        return [
            "msg" => "hey from BinServ", 
            "debug" => $this->binRepo->test()
        ];

    }
}

?>