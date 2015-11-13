<?php

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
