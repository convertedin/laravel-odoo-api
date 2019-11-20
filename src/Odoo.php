<?php

namespace Obuchmann\LaravelOdooApi;

use Obuchmann\LaravelOdooApi\Exceptions\OdooException;
use Obuchmann\LaravelOdooApi\Odoo\CommonEndpoint;
use Obuchmann\LaravelOdooApi\Odoo\Config;
use Obuchmann\LaravelOdooApi\Odoo\ConfigFactory;
use Obuchmann\LaravelOdooApi\Odoo\ObjectEndpoint;
use Obuchmann\LaravelOdooApi\Odoo\Request\RequestBuilder;
use Obuchmann\LaravelOdooApi\Odoo\Response\VersionResponse;
use Obuchmann\LaravelOdooApi\Support\Proxy;
use Illuminate\Support\Collection;
use Ripcord\Client\Client;
use Ripcord\Ripcord;

/**
 * Class Odoo
 *
 * A Plain assembly Class
 *
 * @package Obuchmann\LaravelOdooApi
 * @method Odoo username(string $username)
 * @method Odoo password(string $password)
 * @method Odoo db(string $db)
 * @method Odoo host(string $host)
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
     * @throws Exceptions\ConfigurationException
     * @throws \Ripcord\Exceptions\ConfigurationException
     */
    function __construct()
    {
        $this->loadConfigData();
    }

    /**
     * Load data from config file.
     * @throws Exceptions\ConfigurationException
     * @throws \Ripcord\Exceptions\ConfigurationException
     */
    protected function loadConfigData()
    {
        $this->configFactory = new ConfigFactory(laravelOdooApiConfig());

        $this->proxy($this->configFactory, [
            'host' => 'setHost',
            'db' => 'setDb',
            'username' => 'setUsername',
            'password' => 'setPassword',
            'apiSuffix' => 'setSuffix',
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
    }


    /**
     * Login to Odoo ERP.
     *
     * @param string $db
     * @param string $username
     * @param string $password
     * @param array $array
     * @return $this
     * @throws OdooException
     */
    public function connect($db = null, $username = null, $password = null, array $array = [])
    {

        if($db){
            $this->db($db);
        }
        if($username){
            $this->username($username);
        }
        if($password){
            $this->password($password);
        }
//       if($array){
//           $this->context($array);
//       }

        $uid = $this->authenticate();
        $this->objectEndpoint->setUid($uid);

        return $this;
    }




    /**
     * Create a single record and return its database identifier.
     *
     * @param string $model
     * @param array $data
     * @return integer
     */
    public function create($model, array $data)
    {
        $method = 'create';

        $result = $this->call($model, $method, [$data]);


        return $this->makeResponse($result, 0);
    }

    /**
     * Update one or more records.
     * returns true except when an error happened.
     *
     * @param string $model
     * @param array $data
     * @return true|string
     * @throws OdooException
     */
    public function update($model, array $data)
    {
        if ($this->hasNotProvided($this->condition))
            return "To prevent updating all records you must provide at least one condition. Using where method would solve this.";

        $method = 'write';

        $ids = $this->search($model);

        //If string it can't continue for retrieving models
        //Throw exception with the error.
        if (is_string($ids))
            throw new OdooException($ids);

        $result = $this->call($model, $method, [$ids->toArray(), $data]);

        return $this->makeResponse($result, 0);
    }

    /**
     * Remove a record by Id or Ids.
     * returns true except when an error happened.
     *
     * @param string $model
     * @param array|Collection|int $id
     * @return true|string
     */
    public function deleteById($model, $id)
    {
        if ($id instanceof Collection)
            $id = $id->toArray();

        $method = 'unlink';

        $result = $this->call($model, $method, [$id]);

        return $this->makeResponse($result, 0);
    }

    /**
     * Remove one or a group of records.
     * returns true except when an error happened.
     *
     * @param string $model
     * @return true|string
     * @throws OdooException
     */
    public function delete($model)
    {
        if ($this->hasNotProvided($this->condition))
            return "To prevent deleting all records you must provide at least one condition. Using where method would solve this.";

        $ids = $this->search($model);

        //If string it can't continue for retrieving models
        //Throw exception with the error.
        if (is_string($ids))
            throw new OdooException($ids);

        return $this->deleteById($model, $ids);
    }

    /**
     * Run execute_kw call with provided params.
     *
     * @param $params
     * @return Collection
     */
    public function call($params)
    {
        //Prevent user forgetting connect with the ERP.
        $this->autoConnect();

        $args = array_merge(
            [$this->db, $this->uid, $this->password],
            func_get_args()
        );

        $response = call_user_func_array([$this->object, 'execute_kw'], $args);

        return collect($response);
    }


    /**
     * **********
     * END API LIST
     * **********
     */


    /**
     * Prepare the api response.
     * If there is a faultCode then return its value.
     * If key passed, returns the value of that key.
     * Otherwise return the provided data.
     *
     * @param Collection $result
     * @param string $key
     * @param null $cast Cast returned data based on this param.
     * @return mixed
     */
    protected function makeResponse($result, $key = null, $cast = null)
    {
        if (array_key_exists('faultCode', $result->toArray()))
            throw new OdooException($result);

        if (!is_null($key) && array_key_exists($key, $result->toArray()))
            $result = $result->get($key);

        if ($cast) settype($result, $cast);

        return $result;
    }


    /**
     * Check if user has provided a passed parameter.
     * @param $param
     * @return bool
     */
    protected function hasNotProvided($param)
    {
        return !$param;
    }

    /**
     * Auto connect with the ERP if there isn't uid.
     */
    protected function autoConnect()
    {
        if (!$this->uid) $this->connect();
    }
}