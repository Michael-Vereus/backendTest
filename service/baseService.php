<?php

abstract class BaseService {
    protected function isEmptyArray(array $array): bool{
        if(empty($array)){ return false;} return true;
    }
    protected function errorMethodHandler(): array{
        return ["status"=>"err_Request","msg"=>"Wrong Request Method"];
    }
}

?>