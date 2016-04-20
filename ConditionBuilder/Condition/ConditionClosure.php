<?php

namespace App\Model\Core\DB\Condition;


class ConditionClosure extends BaseCondition
{
    protected $closure;
    
    public function __construct($fieldName, \Closure $closure)
    {
        $this->fieldName = $fieldName;
        $this->closure = $closure;
    }
    
    public function getSqlString()
    {
        $closure = $this->closure;
        return $closure($this->fieldName);
    }
}