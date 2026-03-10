<?php

abstract class BaseRepo{
    protected function createPlaceholder(array $times):string{ // placeholder for query, prevents SQL Injection
        return str_repeat('?, ', count($times)-1). '?';
    }
    protected function rowAffected(PDOStatement $query): bool{ // check if theres an affected row and return a bool
        $affectedRow = $query->rowCount();
        if($affectedRow === 0){return false;}
        return true;
    }
    protected function hasValue(array $checkArray):bool{
        if(empty($checkArray)){return false;}
        return true;
    }
}

?>