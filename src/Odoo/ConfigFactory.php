<?php


namespace Obuchmann\LaravelOdooApi\Odoo;


use Obuchmann\LaravelOdooApi\Exceptions\ConfigurationException;

class ConfigFactory
{

    protected $config;

    /**
     * Config constructor.
     *
     * Prepare with config values
     *
     * @param array $config
     * @throws ConfigurationException
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return Config
     * @throws ConfigurationException
     */
    public function build(): Config
    {
        return new Config(
            $this->getRequired('database'),
            $this->getRequired('host'),
            $this->getRequired('username'),
            $this->getRequired('password'),
            laravelOdooApiAddCharacter(data_get($this->config, 'suffix', '/xmlrpc/2'), '/'),
            laravelOdooApiRemoveCharacter(data_get($this->config, 'encoding', 'utf-8'), '/')
        );
    }

    private function getRequired(string $key)
    {
        if(!array_key_exists($key, $this->config)){
            throw new ConfigurationException("Missing required config $key");
        }
        return $this->config[$key];
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->config['host'] = $host;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database): void
    {
        $this->config['database'] = $database;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->config['username'] = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->config['password'] = $password;
    }

    /**
     * @param mixed $suffix
     */
    public function setSuffix($suffix): void
    {
        $this->config['suffix'] = $suffix;
    }

    /**
     * @param mixed $encoding
     */
    public function setEncoding($encoding): void
    {
        $this->config['encoding'] = $encoding;
    }



}