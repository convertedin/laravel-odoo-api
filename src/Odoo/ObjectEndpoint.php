<?php


namespace Obuchmann\LaravelOdooApi\Odoo;


use Obuchmann\LaravelOdooApi\Odoo\Response\BooleanResponse;
use Obuchmann\LaravelOdooApi\Odoo\Response\Response;

class ObjectEndpoint extends Endpoint
{

    /**
     * @var int
     */
    protected $uid;

    public function setUid(int $uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }


    public function __construct(ConfigFactory $configFactory)
    {
        parent::__construct($configFactory, Endpoint::OBJECT_ENDPOINT_NAME);
    }

    public function newRequest()
    {
        return $this->getRequestFactory()
            ->newRequest($this->getConfig())
            ->setUid($this->uid);
    }


    public function can($permission, $model, $withExceptions = false)
    {
        $request = $this->newRequest()
            ->setModel($model)
            ->setMethod('check_access_rights')
            ->setArguments([$permission])
            ->setOption('raise_exception', $withExceptions)
            ->addResponseClass(BooleanResponse::class)
            ->build();

        return $request->get();
    }

    #region Shorthand Init Methods
    public function where($field, $operator, $value)
    {
        return $this->newRequest()
            ->where($field, $operator, $value);
    }

    public function limit($limit, $offset = 0)
    {
        return $this->newRequest()
            ->limit($limit, $offset);
    }

    public function fields($fields)
    {
        $fields = is_array($fields) ? $fields : func_get_args();
        return $this->newRequest()
            ->fields($fields);
    }

    public function model($model)
    {
        return $this->newRequest()
            ->model($model);
    }

    /**
     * @param $model
     * @return mixed
     * @throws \Obuchmann\LaravelOdooApi\Exceptions\OdooException
     */
//    public function count($model)
//    {
//        return $this->newRequest()
//            ->model($model)
//            ->count();
//    }

    #endregion

}