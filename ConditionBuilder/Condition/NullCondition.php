<?php

namespace App\Model\Core\DB\Condition;


class NullCondition implements ICondition
{
    public function __construct() {}
    
    public function getSqlString()
    {
        return '';
    }

    public function getValue()
    {
        return null;
    }
    
    public function getOperator()
    {
        return null;
    }
}