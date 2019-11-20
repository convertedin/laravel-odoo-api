<?php


namespace Edujugon\Laradoo\Odoo\Request;


use Edujugon\Laradoo\Odoo\Client;
use Edujugon\Laradoo\Odoo\Response\Response;
use Edujugon\Laradoo\Odoo\ResponseFactory;

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

    protected $db;

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
     * @param $db
     * @param $uid
     * @param $password
     * @param $model
     * @param $method
     * @param array|string|null $arguments
     * @param array|string|null $options
     * @param array|string|null $responseClasses
     */
    public function __construct(Client $client, ResponseFactory $responseFactory, $db, $uid, $password, $model, $method, $arguments, $options, $responseClasses)
    {
        $this->client = $client;
        $this->responseFactory = $responseFactory;
        $this->db = $db;
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
            $this->db, $this->uid, $this->password,
            $this->model,
            $this->method,
            $this->arguments,
            $this->options
        ];
    }

    /**
     * @return Response
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function getResponse(): Response
    {
        $response = call_user_func([$this->client, 'execute_kw'],
            $this->db, $this->uid, $this->password,
            $this->model, $this->method,
            $this->arguments,
            $this->options
        );

        return $this->responseFactory->makeResponse($response, $this->responseClasses);
    }

    /**
     * @return mixed
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function get()
    {
        return $this->getResponse()->unwrap();
    }

}