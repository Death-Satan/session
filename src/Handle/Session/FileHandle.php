<?php

namespace DeathSatan\Session\Handle\Session;

use DeathSatan\Session\Driver\DefaultSession;
use DeathSatan\Session\Exceptions\HandleType;
use DeathSatan\Session\Interfaces\Session;

class FileHandle implements \DeathSatan\Session\Interfaces\SessionHandle
{
    protected array $config = [
        'save_path'=>null,//session保存位置
        'name'=>'PHPSESSID',
        'expire'=>3600
    ];

    protected string $dir = '';


    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config,$config);
    }

    /**
     * @throws HandleType
     */
    public function init()
    {
        //检测session保存目录
        if ($this->config['save_path']===null || !is_dir($this->config['save_path'])){
            if (function_exists('sys_get_temp_dir')){
                $this->config['save_path'] = rtrim(rtrim(sys_get_temp_dir(),'\\'),'/').DIRECTORY_SEPARATOR;
            }else{
                throw new HandleType('配置项 save_path 不是有效的目录,请重新设置');
            }
        }
        $this->config['expire'] = time()+$this->config['expire'];

        session_start([
            'name'=>$this->config['name'],
            'save_path'=>$this->config['save_path']
        ]);
    }


    /**
     * @inheritDoc
     */
    public function close()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroy($session_id): bool
    {
        $filepath = $this->get_filepath($session_id);
        if (is_file($filepath)){
            @unlink($filepath);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    protected function get_filepath($session_id): string
    {
        return $this->dir.$session_id;
    }

    /**
     * @inheritDoc
     */
    public function open($save_path, $name): bool
    {
        $dir = $save_path .$name.DIRECTORY_SEPARATOR;
        $this->dir = $dir;
        if (is_dir($dir)){
            return true;
        }else{
            return mkdir($dir);
        }
    }

    /**
     * @inheritDoc
     */
    public function read($session_id)
    {
        $file_path = $this->get_filepath($session_id);
        $file = fopen($file_path,'a+');
        $content= '';
        while (!feof($file)){
            $content.=fgets($file);
        }
        if (empty($content)){
            return '';
        }
        $content = unserialize(base64_decode($content));
        if (!empty($content['expire']) && $content['expire'] < time()) {
            return '';
        }else{
            return empty($content['data'])?'':$content['data'];
        }
    }

    /**
     * @inheritDoc
     */
    public function write($session_id, $session_data): bool
    {
        $file_path = $this->get_filepath($session_id);
        $data = [
            'expire'=>$this->config['expire'],
            'data'=>$session_data
        ];
        file_put_contents($file_path,base64_encode(serialize($data)));
        return true;
    }

    /**
     * @inheritDoc
     */
    public function create_sid(): string
    {
        return uniqid(md5(time()),true);
    }

    /**
     * @inheritDoc
     */
    public function validateId($session_id): bool
    {
        return true;
    }

    /**
     * @inherritDoc
     */
    public function updateTimestamp($session_id, $session_data): bool
    {
        return true;
    }

    public function getDriver(): Session
    {
        return new DefaultSession();
    }
}