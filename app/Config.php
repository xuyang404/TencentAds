<?php

namespace LanHai\TencentAds;

class Config {

    /**
     * host
     *
     * @var string
     */
    protected $host = "https://api.e.qq.com/v1.1";

    protected static $defaultConfiguration = null;

    /**
     * apiKyes
     *
     * @var array
     */
    protected $apiKeys = []; 

    /**
     * set config
     *
     * @param array $config
     */
    protected function __construct()
    {
    }

    /**
     * set host
     *
     * @param string $host
     * @return Config
     */
    public function setHost(string $host) {
        $this->host = $host;
        return $this;
    }

    /**
     * get host
     *
     * @param [type] $host
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * setApiKeys
     *
     * @param array $config
     * @return Config
     */
    public function setApiKeys($config = []) {
        foreach ($config as $key => $value) {
            $this->setApiKey($key, $value);
       } 
       return $this;
    }

    /**
     * getApiKeys
     *
     * @return array
     */
    public function getApiKeys() {
       return $this->apiKeys;
    }

    /**
     * Undocumented setApiKey
     *
     * @param string $key
     * @param string $value
     * @return Config
     */
    public function setApiKey(string $key, string $value)
    {
       $this->apiKeys[$key] = $value;
       return $this;
    }

    /**
     * Undocumented getApiKey
     *
     * @param string $key
     * @return string
     */
    public function getApiKey(string $key)
    {
       return $this->apiKeys[$key];
    }


    /**
     * Gets the default configuration instance
     *
     * @return Config
     */
    public static function getDefaultConfiguration()
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new Config();
        }

        return self::$defaultConfiguration;
    }
}