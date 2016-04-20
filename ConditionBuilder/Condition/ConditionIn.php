<?php

namespace App\Model\Core\DB\Condition;

use App\Model\Core\Registry;

class ConditionIn extends BaseCondition
{
    /** @var \App\Model\Core\DB\DBManager */
    protected $db;
    protected $op = 'IN';
    
    protected $fieldValues;
    
    public function __construct($fieldName, array $fieldValues)
    {
        $this->db = Registry::getInstance(Registry::DB);
        
        $fieldValues = $this->db->escape($fieldValues);

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