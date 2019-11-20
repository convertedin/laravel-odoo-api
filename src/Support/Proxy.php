<?php


namespace Edujugon\Laradoo\Support;


trait Proxy
{
    protected $proxies;

    private static $FLUENT_PREFIX = "|";

    protected function proxy($class, array $methods, bool $fluent = false)
    {
        $prefix = $fluent ? self::$FLUENT_PREFIX : "";
        foreach ($methods as $alias => $method) {
            if(is_numeric($alias)){
                $this->proxies[$prefix.$method] = [$class, $method];
            }else{
                $this->proxies[$prefix.$alias] = [$class, $method];
            }
        }
    }

    public function __call($name, $arguments)
    {
        if(array_key_exists($name, $this->proxies)){
            return call_user_func_array($this->proxies[$name],$arguments);
        }
        if(array_key_exists(self::$FLUENT_PREFIX.$name, $this->proxies)){
            call_user_func_array($this->proxies[self::$FLUENT_PREFIX.$name],$arguments);
            return $this;
        }
        throw new \Exception("Invalid Method $name called");
    }

}