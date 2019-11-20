<?php


namespace Edujugon\Laradoo\Odoo\Response;


use Edujugon\Laradoo\Exceptions\OdooException;

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