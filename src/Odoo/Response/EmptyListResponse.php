<?php


namespace Edujugon\Laradoo\Odoo\Response;


class EmptyListResponse extends EmptyResponse
{
    public function unwrap()
    {
        return collect();
    }
}