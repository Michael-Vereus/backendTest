<?php

abstract class BaseService {
    /**
    *to check an  Array if its empty or not, 
    *then return a bool if empty then false else true
    */ 
    protected function isEmptyArray(array $array): bool{
        if(empty($array)){ return false;} return true;
    }
    // to return array for catching wrong req method with consistency
    protected function errorMethodHandler(): array{
        return [
            "status"=>false,
            "code"=>"request_err"
        ];
    }
    /**
     * to construct a return array, used for data consistency
     */
    protected function getReturnArray(bool $status,string $code,?array $data = null){
        return [
            "status"=>$status,
            "code"=>$code,
            "data"=>$data ?? ["msg"=>"No Data Received"]
        ];
    }
    /**
     * to check if a status is true or not
     * if true then return OK else repo_err
     */
    protected function isTrue(bool $bool): string{
        if($bool){ return "OK";} return "repo_err";
    }

    protected function returnUnknownAction(string $action): array{
        return [
            "status"=>false,
            "action_request"=>$action
        ];
    } 
    
}

?>