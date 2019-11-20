<?php
/**
 * Project: laradoo.
 * User: Edujugon
 * Email: edujugon@gmail.com
 * Date: 10/5/17
 * Time: 16:04
 */
namespace Edujugon\Laradoo\Exceptions;

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