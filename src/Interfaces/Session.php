<?php

namespace DeathSatan\Session\Interfaces;

use ArrayAccess;

interface Session extends ArrayAccess
{
    /**
     * 获取给定的session name对应的值
     * 如果给定的session name不存在则返回default
     * @param string $name
     * @param $default
     * @return mixed
     */
    public function get(string $name,$default=null);

    /**
     * 检测给定的session name是否存在以及值的存在
     * @param string $name
     * @return bool
     */
    public function exists(string $name):bool;

    /**
     * 删除给定的session name
     * @param string $name
     * @return bool
     */
    public function delete(string $name):bool;

    /**
     * 把给定的session name对应的值自增
     * @param string $name
     * @param int $incrementBy 自增值
     * @return int
     */
    public function increment(string $name,int $incrementBy = 1):int;

    /**
     * 把给定的session值自减
     * @param string $name
     * @param int $decrement 自减值
     * @return int
     */
    public function decrement(string $name,int $decrement = 1):int;

    /**
     * 闪存一条消息,只对下一次请求有效
     * @param string $name
     * @param $value
     * @return bool
     */
    public function flash(string $name,$value):bool;

    /**
     * 设置一条session
     * @param string $name
     * @param $value
     * @return bool
     */
    public function set(string $name,$value):bool;

    /**
     * 重新生成session id
     * 此操作不会重置session 数据
     * @return bool
     */
    public function regenerate():bool;

    /**
     * 重新生成session id
     * 此操作会重置当前session内的所有数据
     * @return bool
     */
    public function invalidate():bool;

    /**
     * 保存session数据
     * 此操作将会在response发送之前进行调用
     * @return bool
     */
    public function save():bool;

    /**
     * 添加一条数据到session数组内
     * @param string $name
     * @param $value
     * @return void
     */
    public function push(string $name,$value):void;

    /**
     * 获取一条数据,并在获取后删除掉这条数据
     * @param string $name
     * @param $default
     * @return mixed
     */
    public function pull(string $name,$default=null);
}