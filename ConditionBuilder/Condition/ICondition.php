<?php

namespace SqlQueryBuilder\ConditionBuilder\Condition;


interface ICondition
{
    public function getSqlString();
}