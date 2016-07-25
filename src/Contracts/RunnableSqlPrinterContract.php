<?php

/**
 * @author Kim Hansen <moccalotto@gmail.com>
 * @package PdoTools
 * @copyright Copyright (c) 2016, Kim Hansen
 */

namespace Moccalotto\PdoTools\Contracts;

interface RunnableSqlPrinterContract
{
    /**
     * Render runnable sql
     *
     * @param string $query
     * @param array $bindings
     *
     * @return $string
     */
    public function render($query, array $bindings);
}
