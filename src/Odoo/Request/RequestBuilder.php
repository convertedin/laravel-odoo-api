<?php


namespace Edujugon\Laradoo\Odoo\Request;


use Carbon\Traits\Options;
use Edujugon\Laradoo\Exceptions\OdooException;
use Edujugon\Laradoo\Odoo\Client;
use Edujugon\Laradoo\Odoo\Response\ListResponse;
use Edujugon\Laradoo\Odoo\Response\NumericResponse;
use Edujugon\Laradoo\Odoo\Response\ScalarResponse;
use Edujugon\Laradoo\Odoo\ResponseFactory;
use Illuminate\Support\Collection;

class RequestBuilder
{
    protected $db;

    protected $uid;

    protected $password;

    protected $model;

    protected $method;

    protected $arguments = [];

    /**
     * @var OptionsBuilder
     */
    protected $options;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var array
     */
    protected $responseClasses;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * RequestBuilder constructor.
     */
    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
        $this->options = new OptionsBuilder();
    }


    public function build()
    {
        return new Request(
            $this->client,
            $this->responseFactory,
            $this->db,
            $this->uid,
            $this->password,
            $this->model,
            $this->method,
            $this->getArguments(),
            $this->options->build(),
            $this->responseClasses
        );
    }


    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function addArgument($argument)
    {
        $this->arguments[] = $argument;
        return $this;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function setOptions(OptionsBuilder $options)
    {
        $this->options = $options;
        return $this;
    }

    public function setOption($key, $value)
    {
        $this->options->set($key, $value);
        return $this;
    }

    public function setUid(int $uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @param Client $client
     * @return RequestBuilder
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param ResponseFactory $responseFactory
     * @return RequestBuilder
     */
    public function setResponseFactory(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        return $this;
    }

    /**
     * @param array $responseClasses
     * @return RequestBuilder
     */
    public function setResponseClasses(array $responseClasses)
    {
        $this->responseClasses = $responseClasses;
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function addResponseClass($class)
    {
        $this->responseClasses[] = $class;
        return $this;
    }


    public function getArguments()
    {
        return $this->arguments;
    }

    #region Query Shorthands

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($field, $operator, $value)
    {
        $this->queryBuilder->addWhere($field, $operator, $value);
        return $this;
    }


    public function limit($limit, $offset = 0)
    {
        $this->setOption('limit', $limit);
        $this->setOption('offset', $offset);
        return $this;
    }

    public function fields($fields)
    {
        $fields = is_array($fields) ? $fields : func_get_args();
        $this->setOption('fields', $fields);
        return $this;
    }

    public function model($model)
    {
        $this->setModel($model);
        return $this;
    }

    #endregion

    #region Method shorthands

    /**
     * @param null $model
     * @return mixed
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function count()
    {
        $this->method = 'search_count';
        $this->addResponseClass(NumericResponse::class);

        $this->addArgument($this->queryBuilder->build());

        $request = $this->build();

        return $request->get();
    }

    /**
     * @return mixed
     * @throws OdooException
     */
    public function search()
    {
        $this->method = 'search';
        $this->addResponseClass(ListResponse::class);

        $this->addArgument($this->queryBuilder->build());

        $request = $this->build();

        return $request->get();
    }

    /**
     * @param $ids array|Collection
     * @return mixed
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function read($ids)
    {
        if ($ids instanceof Collection) {
            $ids = $ids->all();
        }
        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        if (!is_array($ids)) {
            throw new OdooException("Invalid type given for ids");
        }

        $this->method = 'read';
        $this->addResponseClass(ListResponse::class);
        $this->arguments = [$ids];

        $request = $this->build();

        return $request->get();

    }

    public function get()
    {
        $this->method = 'search_read';
        $this->addResponseClass(ListResponse::class);

        $this->addArgument($this->queryBuilder->build());

        $request = $this->build();

        return $request->get();
    }

    public function listModelFields($attributes = ['string', 'help', 'type'])
    {
        $this->method = 'fields_get';
        $this->addResponseClass(ListResponse::class);

        $this->options->set('attributes', $attributes);

        $request = $this->build();

        return $request->get();
    }

    #endregion

}