<?php

namespace DeathSatan\Session\Interfaces;

use ArrayAccess;

/**
 * Cookie驱动标准接口
 */
interface CookieHandle extends ArrayAccess
{
    public function __construct(array $config = []);

    /**
     * cookie开始时调用此函数
     * @return mixed
     */
    public function init();

    /**
     * 获取设置的cookie值
     * @param string $name
     * @param $default
     * @return mixed
     */
    public function get(string $name,$default=null);


    /**
     * 设置cookie
     * @param string $name cookie名称
     * @param string $value cookie值
     * @param array $option cookie临时配置项
     * @return mixed
     */
    public function set(string $name,string $value,array $option=[]);

    /**
     * 检测cookie是否存在或者是否过期
     * @param string $name
     * @return bool
     */
    public function exists(string $name):bool;

    /**
     * 删除一个cookie
     * @param string $name
     * @return void
     */
    public function delete(string $name):void;

    /**
     * 保存cookie
     * 此操作会在服务端发送response时调用
     * @return void
     */
    public function save():void;
}