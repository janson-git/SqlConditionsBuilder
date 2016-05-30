<?php

// SAMPLE OF CODE USAGE
$loader = include __DIR__ . '/vendor/autoload.php';
$loader->setPsr4('SqlQueryBuilder\\', './');

use SqlQueryBuilder\ConditionBuilder;
use SqlQueryBuilder\ConditionBuilder\Condition;

$db = [
    'type' => 'pgsql', // db type
    'name' => 'my_example_db',
    'host' => 'A.B.C.D', // hostname or IP
    'port' => '5432', // 5432 is default for Postgres, 3306 is default for MySQL
    'user' => 'test',
    'pass' => 'test'
];
// it used to escape string values in Conditions
$pdo = new \PDO("{$db['type']}:dbname={$db['name']};host={$db['host']};port={$db['port']}", $db['user'], $db['pass']);
ConditionBuilder\Sanitizer::setPdoConnection($pdo);

// and now create conditions string
$conditionOne = new Condition\ConditionEqual('one', true);
$conditionTwo = new Condition\ConditionIn('two', [1,2,3,4]);
$conditionThree = new Condition\ConditionEqual('three', 'example');
$conditionFour = new Condition\ConditionGreaterThan('four', '2015-09-11');

$bagOr = new Condition\ConditionBagOr([$conditionOne, $conditionTwo]);
$bagAnd = new Condition\ConditionBagAnd([$conditionThree, $conditionFour]);

$mainBag = new Condition\ConditionBagAnd([$bagOr, $bagAnd]);

// output is: ( ("one" = TRUE OR "two" IN (1,2,3,4))  AND  ("three" = 'example' AND "four" > '2015-09-11') ) 
echo $mainBag->getSqlString();


