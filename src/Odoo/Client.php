<?php


namespace Obuchmann\LaravelOdooApi\Odoo;


use Ripcord\Client\Transport\Stream;
use Ripcord\Ripcord;

class Client
{

    protected $ripcordClient;
    protected $config;

    public function __construct($url, $options = null, $transport = null)
    {
        $this->ripcordClient = Ripcord::client($url, $options, $transport);
    }

    public function __call($name, $arguments)
    {
        //Just Proxy
        return call_user_func_array([$this->ripcordClient, $name], $arguments);
    }

    public function response()
    {
        return $this->ripcordClient->_response;
    }
}