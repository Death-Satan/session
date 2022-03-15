<?php

namespace DeathSatan\Session\Handle\Cookie;

class DefaultHandle implements \DeathSatan\Session\Interfaces\CookieHandle
{

    /**
     * @var array $config cookie配置项
     */
    protected array $config = [
        'expire'=>3600,//过期时间
        'path'=>'/',//规定 cookie 的服务器路径
        'domain'=>'',//规定 cookie 的域名
        'secure'=>false,//规定是否通过安全的 HTTPS 连接来传输 cookie
        'httponly'=>false,//设定cookie httponly
        'samesite'=>'',//设定cookie samesite
    ];

    /**
     * @var array $attribute cookie临时保存成员变量
     */
    protected array $attribute = [];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config,$config);
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->attribute = &$_COOKIE;
        $this->config['expire'] = time()+3600;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $default = null)
    {
        if (!$this->exists($name)){
            return $default;
        }else{
            return $this->attribute[$name];
        }
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, string $value, array $option = [])
    {
        $config = array_merge($this->config,$option);
        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            setcookie($name, $value, [
                'domain' => $config['domain'],
                'httponly' => $config['httponly'],
                'samesite' => $config['samesite'],
                'expires' => $config['expire'],
                'secure' => $config['secure'],
                'path'=>$config['path']
            ]);
        }else{
            setcookie($name,$value,$config['expire'],$config['path'],$config['domain'],$config['secure'],$config['httponly']);
        }
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return !empty($this->attribute[$name]);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $name): void
    {
        unset($this->attribute[$name]);
        $this->set($name,'',[
            'expire'=>time()-3600
        ]);
    }

    /**
     * @inheritDoc
     */
    public function save(): void
    {
        // TODO: Implement save() method.
    }

    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset,$value);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetExists($offset): bool
    {
        return $this->exists($offset);
    }
}