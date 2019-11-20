<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Response;


class EmptyListResponse extends EmptyResponse
{
    public function unwrap()
    {
        return collect();
    }
}