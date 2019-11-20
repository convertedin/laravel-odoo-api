<?php


namespace Obuchmann\LaravelOdooApi\Odoo;


use Obuchmann\LaravelOdooApi\Exceptions\OdooException;
use Obuchmann\LaravelOdooApi\Odoo\Response\FaultCodeResponse;
use Obuchmann\LaravelOdooApi\Odoo\Response\Response;

class ResponseFactory
{

    public function makeResponse($rawResponse, $intendedResponseClasses = null): Response
    {
        //Handled in Request Builder
//        $response = null;
//        if(FaultCodeResponse::applies($rawResponse)){
//            $response = new FaultCodeResponse($rawResponse);
//        }else{
//
//            if(empty($intendedResponseClasses)){
//                //Todo default Responses
//                throw new \Exception("not implemented!");
//            }
//
//
//        }
        $response = static::makeIntendedResponse($rawResponse, $intendedResponseClasses);

        if(!$response){
            throw new OdooException("Unknown Response Type returned!");
        }

        return $response;
    }



    protected function makeIntendedResponse($rawResponse, $intendedResponseClasses) : Response
    {
        if(!is_array($intendedResponseClasses)){
            $intendedResponseClasses = [$intendedResponseClasses];
        }

        foreach ($intendedResponseClasses as $intendedClass) {
            if(call_user_func([$intendedClass, 'applies'], $rawResponse)){
                try {
                    $class = new \ReflectionClass($intendedClass);
                    if(!$class->isSubclassOf(Response::class)){
                        throw new OdooException("Invalid Response Class given!");
                    }
                    return $class->newInstance($rawResponse);
                } catch (\ReflectionException $e) {
                    // Its not possible to use this type -> its ok
                }
            }
        }
        throw new OdooException("No suitable Response Class given!");
    }
}