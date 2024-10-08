<?php

namespace Convertedin\LaravelOdooApi;

use Convertedin\LaravelOdooApi\Exceptions\OdooException;
use Convertedin\LaravelOdooApi\Odoo\CommonEndpoint;
use Convertedin\LaravelOdooApi\Odoo\Config;
use Convertedin\LaravelOdooApi\Odoo\ConfigFactory;
use Convertedin\LaravelOdooApi\Odoo\ObjectEndpoint;
use Convertedin\LaravelOdooApi\Odoo\Request\RequestBuilder;
use Convertedin\LaravelOdooApi\Odoo\Response\VersionResponse;
use Convertedin\LaravelOdooApi\Support\Proxy;
use Illuminate\Support\Collection;
use Ripcord\Client\Client;
use Ripcord\Ripcord;

/**
 * Class Odoo
 *
 * A Plain assembly Class
 *
 * @package Convertedin\LaravelOdooApi
 * @method Odoo username(string $username)
 * @method Odoo password(string $password)
 * @method Odoo database(string $database)
 * @method Odoo host(string $host)
 * @method Odoo apiSuffix(string $apiSuffix)
 *
 * @method Odoo lang(string $lang)
 *
 * @method VersionResponse version()
 * @method int authenticate()
 *
 * @method RequestBuilder newRequest()
 * @method int getUid()
 * @method bool can(string $permission, string $model)
 * @method RequestBuilder where(string $field, string $operator, $value)
 * @method RequestBuilder limit($limit, $offset = 0)
 * @method RequestBuilder fields($fields)
 * @method RequestBuilder model($model)
 *
 */
class Odoo
{

    use Proxy;
    /**
     * @var ConfigFactory
     */
    protected $configFactory;

    /**
     * @var ObjectEndpoint
     */
    protected $objectEndpoint;


    /**
     * Create a new Odoo instance
     * @param array $config
     * @throws Exceptions\ConfigurationException
     * @throws \Ripcord\Exceptions\ConfigurationException
     */
    function __construct(array $config = [])
    {
        $this->loadConfigData($config);
    }

    /**
     * Load data from config file.
     * @param array $config
     * @throws Exceptions\ConfigurationException
     */
    protected function loadConfigData(array $config = [])
    {
        $this->configFactory = new ConfigFactory($config);

        $this->proxy($this->configFactory, [
            'host' => 'setHost',
            'database' => 'setDatabase',
            'username' => 'setUsername',
            'password' => 'setPassword',
            'apiSuffix' => 'setSuffix',
            'encoding' => 'setEncoding',
            'uid' => 'setUid'
        ], true);

        $this->proxy(new CommonEndpoint($this->configFactory), [
            'version',
            'authenticate',
        ]);

        $this->objectEndpoint = new ObjectEndpoint($this->configFactory);

        $this->proxy($this->objectEndpoint,[
            'getUid',
            'newRequest',
            'can',
            'model',
            'where',
            'limit',
            'fields',
        ]);

        $this->proxy($this->objectEndpoint->getContext(),[
            'lang' => 'setLang',
            'companyId' => 'setCompanyId',
            'updateContext' => 'setOptions'
        ], true);

        $this->guard([
            'can',
            'model',
            'where',
            'limit',
            'fields',
        ],[$this, 'connect']);
    }

    /**
     * Tries to connect to Odoo
     * @return $this
     */
    public function connect()
    {
        if(!$this->getUid()){
            $this->forceConnect();
        }

        return $this;
    }

    /**
     * Forces new Login
     * @return $this
     */
    public function forceConnect()
    {
        $uid = $this->authenticate();
        $this->objectEndpoint->setUid($uid);

        return $this;
    }
}