<?php

namespace App\Model\Core\DB\Condition;

use App\Model\Core\DB\ConditionException;
use App\Model\Core\Registry;

class ConditionAnd extends BaseCondition
{
    /** @var \App\Model\Core\DB\DBManager */
    protected $db;
    protected $op = 'AND';
    
    protected $fieldValues;
    
    const OPERATORS_POS = 0;
    const OPERANDS_POS = 1;
    
    public function __construct($fieldName, array $fieldValues)
    {
        if (count($fieldValues[self::OPERATORS_POS]) !== 2 || count($fieldValues[self::OPERANDS_POS]) !== 2) {
            throw new ConditionException("Condition 'AND' expect 2 operands.");
        }
        
        $this->db = Registry::getInstance(Registry::DB);
        $fieldValues = $this->db->escape($fieldValues);

        $this->fieldName = $this->prepareFieldName($fieldName);
        $this->fieldValues = $fieldValues;
    }
    
    public function getSqlString()
    {
        $operator1 = str_replace("'", '', $this->fieldValues[self::OPERATORS_POS][0]);
        $operator2 = str_replace("'", '', $this->fieldValues[self::OPERATORS_POS][1]);
        $operand1 = $this->fieldValues[self::OPERANDS_POS][0];
        $operand2 = $this->fieldValues[self::OPERANDS_POS][1];

        return "{$this->fieldName} {$operator1} {$operand1} {$this->op} {$this->fieldName} {$operator2} {$operand2}";
    }
    
    public function getValue()
    {
        return $this->fieldValues;
    }
}