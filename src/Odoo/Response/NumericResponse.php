<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


class NumericResponse extends ScalarResponse
{

    public static function applies($raw): bool
    {
        return is_numeric($raw);
    }
}