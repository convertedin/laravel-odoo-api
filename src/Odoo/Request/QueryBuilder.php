<?php


namespace Edujugon\Laradoo\Odoo\Request;


class QueryBuilder
{

    protected $conditions = [];

    public function addWhere($field, $operator, $value)
    {
        $this->conditions[] = [$field, $operator, $value];
    }

    public function build()
    {
        return $this->conditions;
    }

    public function isEmpty()
    {
        return empty($this->conditions);
    }

}