<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Request;


class ContextBuilder
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

    public function isEmpty()
    {
        return empty($this->options);
    }

    public function setLang($lang)
    {
        $this->set('lang', $lang);
    }
}