<?php


namespace Convertedin\LaravelOdooApi\Odoo;


use Convertedin\LaravelOdooApi\Exceptions\AuthenticationException;
use Convertedin\LaravelOdooApi\Odoo\Response\VersionResponse;

class CommonEndpoint extends Endpoint
{
    public function __construct(ConfigFactory $configFactory)
    {
        parent::__construct($configFactory, Endpoint::COMMON_ENDPOINT_NAME);
    }

    /**
     * @return Response\Response
     * @throws \Convertedin\LaravelOdooApi\Exceptions\OdooException
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
            $this->getConfig()->getDatabase(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            ['empty' => 'false'] // Context // Bug in v14 - it must not be empty
        );
        if ($uid > 0) {
            return $uid;
        }

        throw new AuthenticationException("Authentication failed!", 0, null, $client->response());
    }
}