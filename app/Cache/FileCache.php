<?php

namespace LanHai\TencentAds\Cache;

use LanHai\TencentAds\Interfaces\CacheInterface;

class FileCache implements CacheInterface
{   

    /**
     * instance
     *
     * @var FileCache
     */
    protected static $instance;

    /**
     * file dir
     *
     * @var string
     */
    protected $fileDir = '';

    protected function __construct()
    {
    }

    /**
     * get content
     *
     * @param string $name
     * @return void
     */
    public function get(string $name)
    { 
        $dir = $this->fileDir.'/runtime';
        $file = $dir.'/'.$name;
        if (is_file($file)) {
            return json_decode(file_get_contents($dir.'/'.$name), true);
        }else{
            return null;
        }
    }

    /**
     * set content
     *
     * @param string $name
     * @param [type] $data
     * @return FileCache
     */
    public function set(string $name, $data)
    {
        $dir = $this->fileDir.'/runtime';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        file_put_contents($dir.'/'.$name, json_encode($data));
        return $this;
    }

    /**
     * del content
     *
     * @param string $name
     * @return void
     */
    public function del(string $name)
    {
        $dir = $this->fileDir.'/runtime';
        unlink($dir.'/'.$name);
    }

    /**
     * getDefaultFileCache
     *
     * @return FileCache
     */
    public static function getDefaultCache()
    {
        if (!self::$instance) {
            self::$instance = new FileCache();
        }
        self::$instance->setFileDir(dirname(__DIR__));
        return self::$instance;
    }

    /**
     * set file dir
     *
     * @param string $dir
     * @return FileCache
     */
    public function setFileDir(string $dir)
    {
        $this->fileDir = $dir;
        return $this;
    }

    /**
     * get file dir
     *
     * @param string $dir
     * @return string
     */
    public function getFileDir()
    {
        return $this->fileDir;
    }
}
