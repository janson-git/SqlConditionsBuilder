<?php

namespace App\Model\Core\DB\Condition;

use App\Model\Core\DB\ConditionException;
use App\Model\Core\Registry;

class ConditionBetween extends BaseCondition
{
    /** @var \App\Model\Core\DB\DBManager */
    protected $db;
    
    protected $fieldValues;
    
    public function __construct($fieldName, array $fieldValues)
    {
        if (count($fieldValues) !== 2) {
            $count = count($fieldValues);
            throw new ConditionException("Condition 'BETWEEN' expect 2 operands, {$count} given.");
        }
        $this->db = Registry::getInstance(Registry::DB);
        
        $fieldValues = $this->db->escape($fieldValues);

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