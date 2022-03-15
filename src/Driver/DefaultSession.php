<?php

namespace DeathSatan\Session\Driver;

use DeathSatan\ArrayHelpers\Arr;

class DefaultSession implements \DeathSatan\Session\Interfaces\Session
{
    /**
     * @var array
     */
    protected array $attributes = [];

    public function __construct()
    {
        $this->attributes = &$_SESSION;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->exists($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset,$value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $default = null)
    {
        return empty(Arr::get($this->attributes,$name))?$default:
            Arr::get($this->attributes,$name);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return !empty($this->attributes[$name]);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $name): bool
    {
        Arr::forget($this->attributes,$name);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function increment(string $name, int $incrementBy = 1): int
    {
        $value = empty($this->attributes[$name])?0:(int)$this->attributes[$name];
        $this->attributes[$name] = $value+$incrementBy;
        return $this->attributes[$name];
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $name, int $decrement = 1): int
    {
        $value = empty($this->attributes[$name])?0:(int)$this->attributes[$name];
        $this->attributes[$name] = $value-$decrement;
        return $this->attributes[$name];
    }

    /**
     * @inheritDoc
     */
    public function flash(string $name, $value): bool
    {
        $this->set($name,$value);
        $this->push('__FLASH__.__NEXT__',$name);
        $data = $this->get('__FLASH__.__CURRENT__',[]);
        Arr::forget($data,$name);
        $this->set('__FLASH__.__CURRENT__',$data);
        return true;
    }

    public function init()
    {

    }

    /**
     * @inheritDoc
     */
    public function set(string $name, $value): bool
    {
        Arr::set($this->attributes,$name,$value);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function regenerate(): bool
    {
        session_regenerate_id(true);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function invalidate(): bool
    {
        $this->regenerate();
        session_destroy();
        return true;
    }

    public function save(): bool
    {
        $this->clearFlashData();
        return true;
    }

    protected function clearFlashData()
    {
        Arr::forget($this->attributes,$this->get('__FLASH__.__CURRENT__',[]));
        if (!empty($next = $this->get('__FLASH__.__NEXT__'))){
            $this->set('__FLASH__.__CURRENT__',$next);
        }else{
            $this->delete('__FLASH__.__CURRENT__');
        }
        $this->delete('__FLASH__.__NEXT__');
    }

    public function push(string $name, $value): void
    {
        $values = $this->get($name,[]);
        $values[] = $value;
        $this->set($name,$values);
    }

    public function pull(string $name, $default = null)
    {
        $value = $this->get($name,$default);
        $this->delete($name);
        return $value;
    }
}