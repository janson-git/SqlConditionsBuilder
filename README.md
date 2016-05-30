# SqlConditionsBuilder

* _only compposer autoloader is used_

Conditions Builder creates SQL string for 'WHERE' part of SQL query.

Main idea is 'condition bag is list of conditions objects, every condition bag - is condition object too'.
Every condition bag will be wrapped with parenthis in SQL.

If you want set many conditions to WHERE, than put conditions in condition bag with suitable type. For example:
```
-- this is ConditionBag of 'AND' type, and contains two conditions: ConditionEqual and ConditionIn
WHERE (field1 = value1 AND field 2 IN (1,2,3,4))
```




Usage:

incoming data in two params: OR и AND. Output of ConditionBuilder is SQL query part of 'WHERE'.

*input:*
```
OR => 
  one => ['=', true],
  two => ['in', 1,2,3,4],
AND =>
  three => ['=', 'example'],
  four => ['>', '2015-09-11'],
```

*output:*
```
( ("one" = true::boolean OR "two" IN (1,2,3,4))  AND  ("three" = 'example' AND "four" > '2015-09-11') ) 
```

$conditionStringSql = new ConditionBuilder($filter, $this->alias))->getSqlString();

### Same example in code:
```
$conditionOne = new ConditionEqual('one', true);
$conditionTwo = new ConditionIn('two', [1,2,3,4]);
$conditionThree = new ConditionEqual('three', 'example');
$conditionFour = new ConditionGreaterThan('four', '2015-09-11');

$bagOr = new ConditionBagOr([$conditionOne, $conditionTwo]);
$bagAnd = new ConditionBagAnd([$conditionThree, $conditionFour]);

$mainBag = new ConditionBagAnd([$bagOr, $bagAnd]);

$sql = $mainBag->getSqlString();
```
