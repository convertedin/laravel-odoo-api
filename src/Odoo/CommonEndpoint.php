<?php


namespace Edujugon\Laradoo\Odoo;


use Edujugon\Laradoo\Exceptions\AuthenticationException;
use Edujugon\Laradoo\Odoo\Response\VersionResponse;

class CommonEndpoint extends Endpoint
{
    public function __construct(ConfigFactory $configFactory)
    {
        parent::__construct($configFactory, Endpoint::COMMON_ENDPOINT_NAME);
    }

    /**
     * @return Response\Response
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function version()
    {
        $response = $this->getClient()->version();
        return $this->getResponseFactory()->makeResponse($response, VersionResponse::class);
    }

    /**
     * @return int
     * @throws AuthenticationException
     */
    public function authenticate(): int
    {
        $client = $this->getClient(true);
        $uid = $client->authenticate(
            $this->getConfig()->getDb(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            [] // Context
        );
        if ($uid > 0) {
            return $uid;
        }

        throw new AuthenticationException("Authentication failed!", 0, null, $client->response());
    }
}