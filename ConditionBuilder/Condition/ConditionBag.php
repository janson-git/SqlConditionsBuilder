<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


class ConditionBag implements ICondition
{
    const TYPE_AND = 'AND';
    const TYPE_OR = 'OR';
    
    /** @var ICondition[] */
    protected $conditions = [];
    protected $type;

    /**
     * ConditionBag constructor.
     * @param array[ICondition] $conditions
     * @param string $type
     * @throws \Exception
     */
    public function __construct(array $conditions, $type = self::TYPE_AND)
    {
        $allowedTypes = [self::TYPE_AND, self::TYPE_OR];
        $this->type = in_array($type, $allowedTypes) ? $type : self::TYPE_AND;
        
        foreach ($conditions as $condition) {
            if (!$condition instanceof ICondition) {
                throw new \Exception('Condition bag values MUST be instance of ICondition');
            }
            
            $this->conditions[] = $condition;
        }
    }
    
    public function setType($type) {
        $allowedTypes = [self::TYPE_AND, self::TYPE_OR];
        if (!in_array($type, $allowedTypes)) {
            throw new \Exception('Wrong condition bag type given');
        }
        $this->type = $type;
    }
    
    public function getSqlString()
    {
        $strings = [];
        
        foreach ($this->conditions as $condition) {
            $strings[] = $condition->getSqlString();
        }
        
        return ' (' . implode(' ' . $this->type . ' ', $strings) . ') ';
    }
    
}