<?php

namespace DeathSatan\Session;

use DeathSatan\Session\Handle\Session\FileHandle;
use DeathSatan\Session\Interfaces\SessionHandle;


class Session implements \DeathSatan\Session\Interfaces\Session
{
    protected SessionHandle $handle;

    protected array $config = [];

    public function __construct(?SessionHandle $handle=null,array $config = [])
    {
        if (empty($handle)){
            $this->handle = new FileHandle();
        }else{
            $this->handle = $handle;
        }
        $this->config = array_merge($this->config,$config);
        $this->init();
    }

    protected function init(){
        session_set_save_handler($this->handle,true);
        $this->handle->init();
        $driver = $this->getDriver();
        if (method_exists($driver,'init')){
            $driver->init();
        }
    }

    protected function getDriver(): Interfaces\Session
    {
        return $this->handle->getDriver();
    }

    private function call($func,$arg)
    {
        return call_user_func_array([$this->getDriver(),$func],array_values($arg));
    }

    public function offsetUnset($offset)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function set(string $name, $value): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function get(string $name, $default = null)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function offsetSet($offset, $value)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function offsetGet($offset)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function exists(string $name): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function decrement(string $name, int $decrement = 1): int
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function invalidate(): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function offsetExists($offset)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function delete(string $name): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function increment(string $name, int $incrementBy = 1): int
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function flash(string $name, $value): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function regenerate(): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function push(string $name, $value): void
    {
        $this->call(__FUNCTION__,get_defined_vars());
    }

    public function pull(string $name, $default = null)
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }

    public function save(): bool
    {
        return $this->call(__FUNCTION__,get_defined_vars());
    }


}