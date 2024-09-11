<?php


namespace Convertedin\LaravelOdooApi\Odoo\Response;


class VersionResponse extends Response
{
    /**
     * @var array
     */
    public $server_version_info;
    /**
     * @var int
     */
    public $protocol_version;
    /**
     * @var string
     */
    public $server_serie;
    /**
     * @var string
     */
    public $server_version;

    public static function applies($raw): bool
    {
        return is_array($raw) && array_key_exists('server_version', $raw);
    }

    public function unwrap()
    {
        return $this->rawResponse;
    }
}