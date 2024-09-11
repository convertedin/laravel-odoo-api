<?php


namespace Convertedin\LaravelOdooApi\Odoo\Request;


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

    public function setCompanyId($companyId)
    {
        $this->set('company_id', $companyId);
    }

    public function setOptions(array $args){
        foreach ($args as $key => $value) {
            $this->set($key, $value);
        }
    }
}