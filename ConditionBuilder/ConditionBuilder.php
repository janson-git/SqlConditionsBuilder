<?php

namespace App\Model\Core\DB;


use App\Model\Core\DB\Condition\ConditionAnd;
use App\Model\Core\DB\Condition\ConditionBag;
use App\Model\Core\DB\Condition\ConditionBagAnd;
use App\Model\Core\DB\Condition\ConditionBagOr;
use App\Model\Core\DB\Condition\ConditionEqual;
use App\Model\Core\DB\Condition\ConditionGreaterThan;
use App\Model\Core\DB\Condition\ConditionGreaterThanOrEqual;
use App\Model\Core\DB\Condition\ConditionIn;
use App\Model\Core\DB\Condition\ConditionLessThan;
use App\Model\Core\DB\Condition\ConditionLessThanOrEqual;
use App\Model\Core\DB\Condition\ConditionNotEqual;
use App\Model\Core\DB\Condition\ConditionNotIn;
use App\Model\Core\DB\Condition\ConditionOr;
use App\Model\Core\DB\Condition\NullCondition;

class ConditionBuilder
{
    protected $alias;
    protected $filter;
    
    public function __construct($filter, $tableAlias)
    {
        if (empty($tableAlias)) {
            throw new \Exception('You must set table alias for Condition Builder');
        }
        
        $this->filter = $filter;
        $this->alias = $tableAlias;
    }
    
    
    public function getSqlString()
    {
        $filter = $this->filter;

        if (empty($filter)) {
            return '';
        }

        $andConditions = [new NullCondition()];
        $orConditions = [new NullCondition()];
        // AND
        if (array_key_exists('AND', $filter)) {
            $andConditions = $this->createConditionsArray($filter['AND']);
        }
        // OR
        if (array_key_exists('OR', $filter)) {
            $orConditions = $this->createConditionsArray($filter['OR']);
        }

        $bags = [];
        array_push($bags, new ConditionBagAnd($andConditions));
        array_push($bags, new ConditionBagOr($orConditions));
        
        return (new ConditionBag($bags))->getSqlString();
    }


    protected function createConditionsArray(array $conditions)
    {
        $array = [];
        foreach ($conditions as $field => $condition) {
            $alias = $this->alias;
            if (!strpos($field, ".")) {
                $field = "{$alias}.{$field}";
            }
            if (!is_array($condition)) {
                $condition = ['=', $condition];
            }
            $array[] = $this->createCondition($field, $condition);
        }
        return $array;
    }
    
    protected function createCondition($fieldName, array $condition)
    {
        list($operator, $operands) = $this->parseCondition($condition);
        if (empty($operands)) {
            throw new \Exception();
        }

        // проверим количество операндов
        if (in_array($operator, ['>', '<', '=', '>=', '<=', '!=', 'like', 'ilike']) && count($operands) !== 1) {
            throw new ConditionBuilderException("Operator '{$operator}' should be used only with one operand");
        }
        if (in_array($operator, ['and', 'or', 'between']) && count($operands) !== 2) {
            throw new ConditionBuilderException("Operator '{$operator}' should be used only with two operands");
        }

        switch ($operator) {
            case '>':
                $operand = array_pop($operands);
                $condition = new ConditionGreaterThan($fieldName, $operand);
                break;
            case '<':
                $operand = array_pop($operands);
                $condition = new ConditionLessThan($fieldName, $operand);
                break;
            case '=':
                $operand = array_pop($operands);
                $condition = new ConditionEqual($fieldName, $operand);
                break;
            case '>=':
                $operand = array_pop($operands);
                $condition = new ConditionGreaterThanOrEqual($fieldName, $operand);
                break;
            case '<=':
                $operand = array_pop($operands);
                $condition = new ConditionLessThanOrEqual($fieldName, $operand);
                break;
            case '!=':
                $operand = array_pop($operands);
                $condition = new ConditionNotEqual($fieldName, $operand);
                break;
            case 'in':
                $condition = new ConditionIn($fieldName, $operands);
                break;
            case 'not in':
                $condition = new ConditionNotIn($fieldName, $operands);
                break;
            case 'and' :
                $condition = new ConditionAnd($fieldName, $operands);
                break;
            case 'or' :
                $condition = new ConditionOr($fieldName, $operands);
                break;
//            case in_array($operator, ['like', 'ilike']):
//                if ($operands[0] === 'null') {
//                    if (in_array($operator, ['=', '!='])) {
//                        $operator = ($operator === '=') ? 'is' : 'is not';
//                    } else {
//                        throw new DataSourceException("Null value can not be compared using '{$operator}'");
//                    }
//                }
//                $string .= "{$operator} {$condition[0]}";
//                break;
            // Специальный внутренний метод. Можно задать только в коде при создании условий выборки.
//            case 'is empty':
//                // нужны образцы для экранированых true и false
//                $sample = [false, true];
//                $db->escape($sample);
//
//                // если пришло условие 'empty = false'
//                if ($condition[0] === $sample[0]) {
//                    $string = " ({$fieldName} IS NOT NULL AND {$fieldName} != '') ";
//                } else {
//                    // и если всё же хочется пустых значений 'empty = true'
//                    $string = " ({$fieldName} IS NULL OR {$fieldName} = '') ";
//                }
//                break;

            default :
                throw new ConditionBuilderException("Unknown condition operator '{$operator}'");
        }
        return $condition;
    }

    /**
     * @param array $condition
     * @return array [$operator, array $operands]
     */
    protected function parseCondition($condition)
    {
        $operator = strtolower(current(array_splice($condition, 0, 1)));
        $operands = $condition;
        return [$operator, $operands];
    }

}