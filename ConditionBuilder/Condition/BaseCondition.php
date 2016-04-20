<?php

namespace App\Model\Core\DB\Condition;


abstract class BaseCondition implements ICondition
{
    protected $fieldName;
    protected $fieldValue;
    
    
    public function getValue()
    {
        if (isset($this->fieldValue)) {
            return $this->fieldValue;
        }
        return null;
    }
    
    public function getOperator()
    {
        if (isset($this->op)) {
            return $this->op;
        }
        return null;
    }

    /**
     * Пришедшее имя поля разбивает на составные части по символу '.' и обрамляет каждую часть в кавычки
     * @param string $fieldName
     * @return string
     */
    protected function prepareFieldName($fieldName)
    {
        $parts = explode('.', $fieldName);
        return implode('.', array_map(function($item) {
            return '"' . $item . '"';
        }, $parts));
    }
}