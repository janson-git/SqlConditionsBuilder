<?php

namespace SqlQueryBuilder\ConditionBuilder;


class Sanitizer
{
    /** @var \PDO */
    private static $pdoConnection = null;

    /**
     * Sets PDO Connection to quote values with PDO functions
     * @param \PDO $pdoConnection
     */
    public static function setPdoConnection(\PDO $pdoConnection)
    {
        self::$pdoConnection = $pdoConnection;
    }

    /**
     * @param array $params Array of params to sanitaze it
     * @param \PDO|null $pdoConnection
     * @return array
     * @throws \Exception
     */
    public static function escape(array &$params, \PDO $pdoConnection = null)
    {
        $connection = ($pdoConnection !== null) ? $pdoConnection : self::$pdoConnection;

        foreach ($params as &$value) {
            if (is_array($value)) {
                $value = self::escape($value);
            } elseif (is_bool($value) || in_array(strtolower($value), ['true', 'false'])) {
                $value = ($value === true || strtolower($value) === 'true') ? 'TRUE' : 'FALSE';
            } elseif (is_null($value) || strtolower($value) === 'null') {
                $value = 'NULL';
            } elseif (is_string($value)) {
                $value = $connection->quote($value, \PDO::PARAM_STR);
            } elseif (is_object($value) || is_resource($value) || is_callable($value)) {
                throw new \Exception("Error in escaping variable of type '" . gettype($value) . "'");
            }
            //numeric types used 'as is'
        }
        return $params;
    }
}