<?php


namespace Convertedin\LaravelOdooApi\Odoo\Request;


use Convertedin\LaravelOdooApi\Odoo\Client;
use Convertedin\LaravelOdooApi\Odoo\Response\Response;
use Convertedin\LaravelOdooApi\Odoo\ResponseFactory;

class Request
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    protected $database;

    protected $uid;

    protected $password;

    protected $model;

    protected $method;

    protected $arguments = [];

    protected $options = [];

    protected $responseClasses;

    /**
     * Request constructor.
     * @param Client $client
     * @param ResponseFactory $responseFactory
     * @param $database
     * @param $uid
     * @param $password
     * @param $model
     * @param $method
     * @param array|string|null $arguments
     * @param array|string|null $options
     * @param array|string|null $responseClasses
     */
    public function __construct(Client $client, ResponseFactory $responseFactory, $database, $uid, $password, $model, $method, $arguments, $options, $responseClasses)
    {
        $this->client = $client;
        $this->responseFactory = $responseFactory;
        $this->database = $database;
        $this->uid = $uid;
        $this->password = $password;
        $this->model = $model;
        $this->method = $method;
        $this->arguments = $arguments;
        $this->options = $options;
        $this->responseClasses = $responseClasses;
    }


    public function toArray()
    {
        return [
            $this->database, $this->uid, $this->password,
            $this->model,
            $this->method,
            $this->arguments,
            $this->options
        ];
    }

    /**
     * @return Response
     * @throws \Convertedin\LaravelOdooApi\Exceptions\OdooException
     */
    public function getResponse(): Response
    {
        $response = call_user_func([$this->client, 'execute_kw'],
            $this->database, $this->uid, $this->password,
            $this->model, $this->method,
            $this->arguments,
            $this->options
        );

        return $this->responseFactory->makeResponse($response, $this->responseClasses);
    }

    /**
     * @return mixed
     * @throws \Convertedin\LaravelOdooApi\Exceptions\OdooException
     */
    public function get()
    {
        return $this->getResponse()->unwrap();
    }

}