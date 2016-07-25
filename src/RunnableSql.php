<?php

/**
 * @author Kim Hansen <moccalotto@gmail.com>
 * @package PdoTools
 * @copyright Copyright (c) 2016, Kim Hansen
 */

namespace Moccalotto\PdoTools;

use PDO;

/**
  * RunnableSql
  *
  * Render PDO sql into runnable sql.
  * Great for logging, etc.
  */
class RunnableSql implements Contracts\RunnableSqlPrinterContract
{
    /**
     * The PDO instance.
     *
     * Used to quote identifiers
     *
     * @var PDO
     */
    public $pdo;

    /**
     * Constructor
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param int|string|bool|float $value
     * @return int
     */
    protected function getParamType($value)
    {
        switch (gettype($value)) {
        case "integer":
            return PDO::PARAM_INT;
        case "boolean":
            return PDO::PARAM_BOOL;
        default:
            return strlen($value) < 1024
                ? PDO::PARAM_STR
                : PDO::PARAM_LOB;
        }
    }

    /**
     * @param string $query
     * @param (int|string|bool|float)[] $bindings
     * @return string
     */
    protected function renderWithArrayBindings($query, array $bindings)
    {
        foreach ($bindings as $value) {
            $query = preg_replace('/[?]/', $this->quoteValue($value), $query, 1);
        }
        return $query;
    }

    /**
     * @param string $query
     * @param array $bindings
     * @return string
     */
    protected function renderWithNamedBindings($query, array $bindings)
    {
        $escaped = [];
        foreach ($bindings as $name => $value) {
            $escaped[$name] = $this->quoteValue($value);
        }
        return strtr($query, $escaped);
    }

    /**
     * @param int|string|bool|float $value
     * @return string
     */
    protected function quoteValue($value)
    {
        $param_type = $this->getParamType($value);
        if (in_array($param_type, [PDO::PARAM_INT, PDO::PARAM_BOOL])) {
            return (int)$value;
        }
        return $this->pdo->quote($value, $this->getParamType($value));
    }

    /**
     * Render runnable sql
     *
     * @see Contracts\RunnableSqlPrinterContract::render
     */
    public function render($query, array $bindings)
    {
        return isset($bindings[0])
            ? $this->renderWithArrayBindings($query, $bindings)
            : $this->renderWithNamedBindings($query, $bindings);
    }
}
