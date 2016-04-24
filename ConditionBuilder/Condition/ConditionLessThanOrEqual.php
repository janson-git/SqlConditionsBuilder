<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


class ConditionLessThanOrEqual extends BaseCondition
{
    protected $op = '<=';
    
    public function __construct($fieldName, $fieldValue)
    {
        $params = [$fieldValue];
        list($fieldValue) = $this->escape($params);

        $this->fieldName = $this->prepareFieldName($fieldName);
        $this->fieldValue = $fieldValue;
    }
    
    public function getSqlString()
    {
        return "{$this->fieldName} {$this->op} {$this->fieldValue}";
    }
}