<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


class BooleanResponse extends ScalarResponse
{
    public static function applies($raw): bool
    {
        return is_bool($raw);
    }
}