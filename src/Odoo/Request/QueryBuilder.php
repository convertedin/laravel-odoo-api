<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Request;


class QueryBuilder
{

    protected array $conditions = [];

    public function addWhere(string $field, string $operator, $value)
    {
        $this->conditions[] = [$field, $operator, $value];
    }

    public function setWheres(array $conditions)
    {
        $this->conditions = $conditions;
    }

    public function build(): array
    {
        return $this->conditions;
    }

    public function isEmpty(): bool
    {
        return empty($this->conditions);
    }

}