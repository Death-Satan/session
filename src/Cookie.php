<?php

namespace DeathSatan\Session;

use DeathSatan\Session\Handle\Cookie\DefaultHandle;
use DeathSatan\Session\Interfaces\CookieHandle;
use ArrayAccess;
class Cookie implements ArrayAccess
{
    protected CookieHandle $handle;
    public function __construct(?CookieHandle $handle=null)
    {
        if ($handle===null){
            $this->handle = new DefaultHandle();
        }else{
        $this->handle = $handle;
        }

        $this->init();
    }

    /**
     * cookie初始化
     * @return void
     */
    public function init()
    {
        $this->handle->init();
    }

    public function get(string $name,$default=null)
    {
        return $this->handle->get($name,$default);
    }

    public function set(string $name,string $value,array $option=[])
    {
        $this->handle->set($name,$value,$option);
    }

    public function exists(string $name):bool
    {
        return $this->handle->exists($name);
    }

    public function offsetUnset($offset)
    {
        unset($this->handle[$offset]);
    }

    public function offsetExists($offset):bool
    {
        return $this->handle->exists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->handle->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->handle->set($offset,$value);
    }
}