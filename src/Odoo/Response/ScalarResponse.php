<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


class ScalarResponse extends Response
{

    public $value;

    public static function applies($raw): bool
    {
        return is_scalar($raw);
    }

    public function apply($raw)
    {
        $this->value = $raw;
    }

    public function unwrap()
    {
        return $this->rawResponse;
    }
}