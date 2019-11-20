<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Request;


class OptionsBuilder
{

    protected $options = [];

    public function set($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function build()
    {
        return $this->options;
    }
}