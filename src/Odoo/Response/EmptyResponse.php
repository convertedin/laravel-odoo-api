<?php


namespace Edujugon\Laradoo\Odoo\Response;


class EmptyResponse extends Response
{

    public static function applies($raw): bool
    {
        return is_array($raw) && array_key_exists('faultCode', $raw) && $raw['faultCode'] == 2;
    }

    public function unwrap()
    {
        return null;
    }

}