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
            case 'touchBin':
                return $this->addBin($incomingData);
                break;
            case 'nanoBin' : 
                return $this->updateBin($incomingData);
                break;
            case 'rmBin' :
                return $this->removeBin($incomingData);
                break;
            default:
                return [
                    "status"=>"err",
                    "msg" => "Unknown action type"
                ];
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
            $ids = $incomingData['binId'] ?? [];
            return $this->binRepo->fetch($ids);
        } else {
            return ["msg" => "wrong req method dumbass"];
        }
    }
    public function addBin($incomingData){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $newBin = new BinEntity(
                $incomingData['binId'],
                $incomingData['binName'],
                $incomingData['Capacity']
            );
            $result = $this->binRepo->save($newBin);
            return $result;
        } else {
            return ["msg"=>"check THE REQ METHOD FOR GODSAKE"];
        }
    }
    public function removeBin($incomingData){
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $ids = $incomingData['binId'] ?? [];
            return $this->binRepo->deleteById($ids);
        } else {
            return ["msg"=>"wromg method dumbass"];
        }
    }
    public function updateBin($incomingData) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $newBin = new BinEntity(
                $incomingData['binId'],
                $incomingData['binName'],
                $incomingData['Capacity']
            );
            $result = $this->binRepo->save($newBin);
            return $result;
        } else {
            return [
                "status" => "err",
                "msg"=>"Wrong Request Method"
            ];
        }
    }
}

?>