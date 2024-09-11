<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


abstract class Response
{
    protected $rawResponse;

    /**
     * Response constructor.
     * @param $raw
     */
    public function __construct($raw)
    {
        $this->rawResponse = $raw;
        $this->apply($raw);
    }

    public abstract static function applies($raw): bool;

    public function apply($rawResponse)
    {
        $vars = get_object_vars($this);
        unset($vars['rawResponse']);
        foreach ($vars as $var => $value) {
            if(array_key_exists($var, $rawResponse)) {
                $this->{$var} = $rawResponse[$var];
            }
        }
    }

    public abstract function unwrap();
}
