<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


class ConditionBagAnd extends ConditionBag implements ICondition
{
    public function __construct(array $conditions)
    {
        parent::__construct($conditions, self::TYPE_AND);
    }
}