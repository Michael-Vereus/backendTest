<?php 

class BinService{
    private $binRepo;

    public function __construct($pdo) {
        $this->binRepo = new BinRepo($pdo);
    }

    public function run($incomingData,$action){
        
        switch ($action) {
            case 'test':
                return $this->test();
                break;
            case 'lsBin':
                return $this->getBin($incomingData);
                break;
            default:
                return ["msg" => "OI WRONG ACTION TYPE BRBUH"];
                break;
        }
    }
    private function test(){
        return [
            "msg" => "hey from BinServ", 
            "debug" => $this->binRepo->test()
        ];

    }
    public function getBin($incomingData){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $ids = $incomingData['id'] ?? [];
            return $this->binRepo->fetch($ids);
        } else {
            return ["msg" => "wrong req method dumbass"];
        }
    }
}

?>