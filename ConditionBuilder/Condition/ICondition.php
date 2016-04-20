<?php

namespace App\Model\Core\DB\Condition;


interface ICondition
{
    public function getSqlString();
}