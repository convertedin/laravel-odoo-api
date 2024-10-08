<?php


namespace Convertedin\LaravelOdooApi\Odoo;


use Convertedin\LaravelOdooApi\Exceptions\ConfigurationException;

class Config
{
    /**
     * Database Name
     *
     * @var string
     */
    protected $database;

    /**
     * Host Name
     *
     * @var string
     */
    protected $host;

    /**
     * DB Username
     *
     * @var string
     */
    protected $username;


    /**
     * DB Password
     *
     * @var string
     */
    protected $password;

    /**
     * API host suffix
     *
     * @var string
     */
    protected $suffix;

    /**
     * Transport Encoding for ripcord
     * @var string
     */
    protected $encoding;

    /**
     * Config constructor.
     * @param string $database
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $suffix
     * @param string $encoding
     * @param int|null $uid
     */
    public function __construct(string $database, string $host, string $username, string $password, string $suffix, string $encoding)
    {
        $this->database = $database;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->suffix = $suffix;
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

}