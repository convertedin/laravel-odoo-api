<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Request;


class OptionsBuilder
{

    protected $options = [];

    protected $context;

    /**
     * OptionsBuilder constructor.
     * @param $context
     */
    public function __construct(ContextBuilder $context = null)
    {
        if ($context) {
            $this->context = $context;
        } else {
            $this->context = new ContextBuilder();
        }
    }


    public function set($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function build(?array $skipOptions = null)
    {
        $options = $this->options;
        if(!empty($skipOptions)){
            // Filter Skipped Options
            $options = collect($options)->filter(function($_, $key) use($skipOptions){
                return array_search($key, $skipOptions) === false;
            })->all();
        }

        if ($this->context->isEmpty()) {
            return $options;
        } else {
            return $options + ['context' => $this->context->build()];
        }
    }

    public function getContext(): ContextBuilder
    {
        return $this->context;
    }

    public function setContext(ContextBuilder $context): OptionsBuilder
    {
        $this->context = $context;
        return $this;
    }


}