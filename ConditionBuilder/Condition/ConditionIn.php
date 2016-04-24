<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


class ConditionIn extends BaseCondition
{
    protected $op = 'IN';
    
    protected $fieldValues;
    
    public function __construct($fieldName, array $fieldValues)
    {
        $fieldValues = $this->escape($fieldValues);

        $this->fieldName = $this->prepareFieldName($fieldName);
        $this->fieldValues = $fieldValues;
    }
    
    public function getSqlString()
    {
        $vals = implode(',', $this->fieldValues);
        return "{$this->fieldName} {$this->op} ({$vals})";
    }

    public function getValue()
    {
        return $this->fieldValues;
    }
}