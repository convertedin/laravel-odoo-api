<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Response;


class ListResponse extends ScalarResponse
{
    public static function applies($raw): bool
    {
        return is_array($raw);
    }

    public function unwrap()
    {
        return collect($this->value);
    }
}