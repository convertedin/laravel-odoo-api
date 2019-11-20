<?php

namespace Obuchmann\LaravelOdooApi\Exceptions;

use Exception;
use Throwable;


class OdooException extends Exception
{
    public $response;
    public function __construct($message = "", $code = 0, Throwable $previous = null, $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }


}