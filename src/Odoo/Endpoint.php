<?php


namespace Convertedin\LaravelOdooApi\Odoo;


abstract class Endpoint
{

    /**
     * Common endpoint
     * meta-calls which don't require authentication
     *
     * @var string
     */
    const COMMON_ENDPOINT_NAME = 'common';

    /**
     * Object endpoint
     *
     * @var string
     */
    const OBJECT_ENDPOINT_NAME = 'object';

    /**
     * @var ConfigFactory
     */
    protected $configFactory;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * Endpoint constructor.
     * @param ConfigFactory $configFactory
     * @param string $name
     */
    public function __construct(ConfigFactory $configFactory, string $name)
    {
        $this->configFactory = $configFactory;
        $this->name = $name;
    }

    protected function getConfig(): Config
    {
        // Delay Config init
        if (null == $this->config) {
            $this->config = $this->configFactory->build();
        }
        return $this->config;
    }

    protected function getClient($forceNew = false): Client
    {
        if ($forceNew || null == $this->client) {
            $config = $this->getConfig();
            $this->url = $config->getHost() . $config->getSuffix() . $this->name;
            $this->client = new Client($this->url, [
                'encoding' => $config->getEncoding()
            ]);
        }
        return $this->client;
    }

    protected function getResponseFactory(): ResponseFactory
    {
        return new ResponseFactory();
    }

    protected function getRequestFactory($forceNew = false): RequestFactory {
        if($forceNew || null == $this->requestFactory){
            $this->requestFactory = new RequestFactory($this->getClient(), $this->getResponseFactory());
        }
        return $this->requestFactory;
    }

}