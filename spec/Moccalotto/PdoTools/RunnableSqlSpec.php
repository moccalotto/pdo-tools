<?php

namespace spec\Moccalotto\PdoTools;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PDO;
use Moccalotto\PdoTools\RunnableSql;

class RunnableSqlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Pdo('sqlite::memory:'));
        $this->shouldHaveType(RunnableSql::class);
    }

    function it_renders_queries_without_bindings()
    {
        $sql = 'SELECT * FROM FoobarTable WHERE id = 123';
        $this->beConstructedWith(new Pdo('sqlite::memory:'));
        $this->render($sql, [])->shouldBe($sql);
    }

    function it_renders_queries_with_numeric_bindings()
    {
        $in_sql = 'SELECT * FROM FoobarTable WHERE some_int = ? AND some_string = ?';
        $out_sql =  'SELECT * FROM FoobarTable WHERE some_int = 1 AND some_string = \'some string\'';
        $bindings = [1, 'some string'];
        $this->beConstructedWith(new Pdo('sqlite::memory:'));
        $this->render($in_sql, $bindings)->shouldBe($out_sql);
    }

    function it_renders_queries_with_named_bindings()
    {
        $in_sql = 'SELECT * FROM FoobarTable WHERE some_int = :SOME_INT AND some_string = :SOME_STRING';
        $out_sql =  'SELECT * FROM FoobarTable WHERE some_int = 1 AND some_string = \'some string\'';
        $bindings = [
            ':SOME_INT' => 1,
            ':SOME_STRING' => 'some string'
        ];
        $this->beConstructedWith(new Pdo('sqlite::memory:'));
        $this->render($in_sql, $bindings)->shouldBe($out_sql);
    }
}
