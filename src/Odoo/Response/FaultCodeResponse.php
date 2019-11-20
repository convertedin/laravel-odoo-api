<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Response;


use Obuchmann\LaravelOdooApi\Exceptions\OdooException;

class FaultCodeResponse extends Response
{
    /**
     * @var int
     */
    public $faultCode;

    /**
     * @var string
     */
    public $faultString;

    public static function applies($raw): bool
    {
        return is_array($raw) && array_key_exists('faultCode', $raw);
    }

    public function unwrap()
    {
        throw new OdooException($this->faultString, $this->faultCode, null, $this->rawResponse);
    }
}