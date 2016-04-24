<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


class ConditionIsNull extends BaseCondition
{
    public function __construct($fieldName)
    {
        $this->fieldName = $this->prepareFieldName($fieldName);
    }
    
    public function getSqlString()
    {
        return "{$this->fieldName} IS NULL";
    }
}