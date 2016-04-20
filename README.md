# SqlQueryBuilder


Only ConditionBuilder is ready now.

Usage:

// incoming data in two params: OR и AND. Output of ConditionBuilder is SQL query part of 'WHERE'.
// *input:*
// OR => 
//    one => ['=', true],
//    two => ['in', 1,2,3,4],
// AND =>
//    three => ['=', 'example'],
//    four => ['>', '2015-09-11'],
//
// *output:*
// _three = 'example' AND four > '2015-09-11' AND (one = TRUE OR two IN (1,2,3,4))_

$conditionStringSql = new ConditionBuilder($filter, $this->alias))->getSqlString();