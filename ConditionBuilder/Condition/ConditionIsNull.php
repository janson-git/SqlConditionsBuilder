<?php

namespace App\Model\Core\DB\Condition;

use App\Model\Core\Registry;

class ConditionIsNull extends BaseCondition
{
    /** @var \App\Model\Core\DB\DBManager */
    protected $db;
    
    public function __construct($fieldName)
    {
        $this->db = Registry::getInstance(Registry::DB);
        
        $this->fieldName = $this->prepareFieldName($fieldName);
    }
    
    public function getSqlString()
    {
        return "{$this->fieldName} IS NULL";
    }
}