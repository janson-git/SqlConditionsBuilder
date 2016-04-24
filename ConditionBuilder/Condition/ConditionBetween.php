<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;

use SqlQueryBuilder\ConditionBuilder\ConditionException;

class ConditionBetween extends BaseCondition
{
    protected $fieldValues;
    
    public function __construct($fieldName, array $fieldValues)
    {
        if (count($fieldValues) !== 2) {
            $count = count($fieldValues);
            throw new ConditionException("Condition 'BETWEEN' expect 2 operands, {$count} given.");
        }
        
        $fieldValues = $this->escape($fieldValues);

        $this->fieldName = $this->prepareFieldName($fieldName);
        $this->fieldValues = $fieldValues;
    }
    
    public function getSqlString()
    {
        return "BETWEEN " . implode(" AND ", $this->fieldValues);
    }

    public function getValue()
    {
        return $this->fieldValues;
    }
}