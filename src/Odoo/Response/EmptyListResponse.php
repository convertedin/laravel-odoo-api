<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


class EmptyListResponse extends EmptyResponse
{
    public function unwrap()
    {
        return collect();
    }
}