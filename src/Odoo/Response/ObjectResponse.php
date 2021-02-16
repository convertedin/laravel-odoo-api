<?php


namespace Obuchmann\LaravelOdooApi\Odoo\Response;


class ObjectResponse extends ScalarResponse
{
    public static function applies($raw): bool
    {
        return is_array($raw) && static::hasStringKey($raw);
    }

    private static function hasStringKey(array $raw)
    {
        foreach(array_keys($raw) as $key){
            if(is_string($key))
                return true;
        }
        return false;
    }

    public function unwrap()
    {
        return (object)$this->value;
    }


}