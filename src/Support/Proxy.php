<?php


namespace Obuchmann\LaravelOdooApi\Support;


trait Proxy
{
    protected $proxies;
    protected $guards = [];

    private static $FLUENT_PREFIX = "|";

    protected function proxy($class, array $methods, bool $fluent = false)
    {
        $prefix = $fluent ? self::$FLUENT_PREFIX : "";
        foreach ($methods as $alias => $method) {
            if (is_numeric($alias)) {
                $this->proxies[$prefix . $method] = [$class, $method];
            } else {
                $this->proxies[$prefix . $alias] = [$class, $method];
            }
        }
    }

    protected function guard($methods, $callable)
    {
        if(!is_array($methods)){
            $methods = [$methods];
        }

        foreach ($methods as $method) {
            if(!array_key_exists($method, $this->guards)){
                $this->guards[$method] = [];
            }
            $this->guards[$method][] = $callable;
        }

    }

    public function __call($name, $arguments)
    {

        // Run Guards
        if (array_key_exists($name, $this->guards) || array_key_exists(self::$FLUENT_PREFIX . $name, $this->guards) ) {
            foreach ($this->guards[$name] as $guard){
                if(false === call_user_func_array($guard, $arguments)){
                    return null;
                }
            }
        }

        if (array_key_exists($name, $this->proxies)) {
            return call_user_func_array($this->proxies[$name], $arguments);
        }
        if (array_key_exists(self::$FLUENT_PREFIX . $name, $this->proxies)) {
            call_user_func_array($this->proxies[self::$FLUENT_PREFIX . $name], $arguments);
            return $this;
        }
        throw new \Exception("Invalid Method $name called");
    }

}