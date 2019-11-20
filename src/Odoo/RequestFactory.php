<?php


namespace Obuchmann\LaravelOdooApi\Odoo;


use Obuchmann\LaravelOdooApi\Odoo\Request\RequestBuilder;

class RequestFactory
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * RequestFactory constructor.
     * @param Client $client
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Client $client, ResponseFactory $responseFactory)
    {
        $this->client = $client;
        $this->responseFactory = $responseFactory;
    }


    public function newRequest(?Config $config = null)
    {
        $request = new RequestBuilder();
        $request->setClient($this->client);
        $request->setResponseFactory($this->responseFactory);

        if ($config) {
            $request
                ->setDb($config->getDb())
                ->setPassword($config->getPassword());
        }
        return $request;
    }


}