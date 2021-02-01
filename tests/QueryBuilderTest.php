<?php

use Obuchmann\LaravelOdooApi\Odoo\Request\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testWhere()
    {
        $result = (new QueryBuilder())
            ->where('id', '=', 1)
            ->build()
        ;

        $this->assertEquals([['id', '=', 1]], $result);

    }

    public function testOr()
    {
        $result = (new QueryBuilder())
            ->where('id', '=', 1)
            ->orWhere('id', '=', 2)
            ->build()
        ;

        $this->assertEquals(['|',['id', '=', 1],['id', '=', 2]], $result);
    }

    public function testOrWhereMix()
    {
        $result = (new QueryBuilder())
            ->where('id', '=', 1)
            ->orWhere('id', '=', 2)
            ->where('company_id', '=', 3)
            ->build()
        ;

        $this->assertEquals(['|',['id', '=', 1],['id', '=', 2], ['company_id', '=', 3]], $result);
    }

    public function testOrWhereMultiple()
    {
        $result = (new QueryBuilder())
            ->where('id', '=', 1)
            ->orWhere('id', '=', 2)
            ->orWhere('company_id', '=', 3)
            ->build()
        ;

        $this->assertEquals(['|',['id', '=', 1], '|', ['id', '=', 2], ['company_id', '=', 3]], $result);
    }

}