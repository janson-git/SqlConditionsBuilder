<?php

namespace App\Model\Core\DB\Condition;

use App\Model\Core\Registry;

class ConditionGreaterThanOrEqual extends BaseCondition
{
    /** @var \App\Model\Core\DB\DBManager */
    protected $db;
    protected $op = '>=';
    
    public function __construct($fieldName, $fieldValue)
    {
        $this->db = Registry::getInstance(Registry::DB);
        
        $params = [$fieldValue];
        list($fieldValue) = $this->db->escape($params);

        $this->fieldName = $this->prepareFieldName($fieldName);
        $this->fieldValue = $fieldValue;
    }
    
    public function getSqlString()
    {
        return "{$this->fieldName} {$this->op} {$this->fieldValue}";
    }
}