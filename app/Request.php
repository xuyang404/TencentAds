<?php

namespace LanHai\TencentAds;

use Curl\Curl;
use LanHai\TencentAds\Cache\FileCache;

class Request
{

    /**
     * $instance
     *
     * @var Request
     */
    protected static $instance;

    /**
     * $client
     *
     * @var Curl
     */
    protected static $client;

    /**
     * $request
     *
     * @var Curl
     */
    protected $request;

    /**
     * $config
     *
     * @var Config
     */
    protected $config;

    /**
     * $config
     *
     * @var FileCache
     */
    protected $cache;

    /**
     * buildRespone中需要处理的code
     *
     * @var array
     */
    protected $errCode = [
        31017,
        18009
    ];

    protected function __construct()
    {
    }

    /**
     * init
     *
     * @param array $config
     * @return Request
     */
    public static function init(array $config = [])
    {
        $instance = self::getInstance();
        $instance->config = Config::getDefaultConfiguration();
        $instance->request = self::getClient();
        $instance->cache = FileCache::getDefaultCache();
        $instance->config->setApiKeys($config);
        return $instance;
    }

    /**
     * setHost
     *
     * @param string $host
     * @return Request
     */
    public function setHost(string $host)
    {
        $this->config->setHost($host);
        return $this;
    }

    /**
     * get
     *
     * @param string $url
     * @param array $data
     * @return void
     */
    public function get(string $url, array $data = [])
    {
        $data = $this->buildRequestData($url, $data);
        $host = $this->config->getHost();
        $resp = $this->request->get($host . '/' . $url, $data);
        return json_decode(json_encode($resp), true);
    }

    public function post(string $url, array $data = [])
    {   
        $data = $this->buildRequestData($url, $data, 'post');
        $query = $data['query'];
        unset($data['query']);
        $host = $this->config->getHost();
        $resp = $this->request->post($host . '/' . $url.'?'.$query, $data);
        return json_decode(json_encode($resp), true);
    }

    /**
     * getConfig
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * setConfig
     *
     * @return Config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * get Cache
     *
     * @return
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * get Cache
     *
     * @return Request
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * build Request Data
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function buildRequestData(string $url, array $data, string $action = 'get')
    {
        if (!isset($data['fields']) && ($action == 'get')) {
            $name = explode("/", $url)[0];
            $fields = $this->cache->get($name);
            // if (!$fields) {
            //     $fields = $this->getFields($name, $url);
            //     $this->cache->set($name, $fields);
            // }           
            $data['fields'] = $fields;
        }

        if ($action == 'post') {
            $params = $this->config->getApiKeys();
            foreach ($params as $key => $value) {
                $data['query'][$key] = $value;
            }
            $data['query']['nonce'] = uniqid(time() . rand(0, 1000));
            $data['query']['timestamp'] = time();
            $data['query'] = http_build_query($data['query']);
        }else{
            $params = $this->config->getApiKeys();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['nonce'] = uniqid(time() . rand(0, 1000));
            $data['timestamp'] = time();
        }

        return $data;
    }

    // /**
    //  * get Fields
    //  *
    //  * @param string $url
    //  * @return void
    //  */
    // protected function getFields(string $name, string $url) {
    //     $url  = str_replace("/","_", $url);
    //     $resp = $this->request->get("https://developers.e.qq.com/docs/api/adsmanagement/{$name}/{$url}?version=1.2");
    //     preg_match("/\<code class=\"Json hljs\"\>([\s\S]+)\<\/code\>/" ,$resp, $matches);
    //     $list = json_decode($matches[1], true)['data']['list'];
    //     $fields = array_keys($list[0]);
    //     return $fields;
    // }

    // /**
    //  * build Response Data
    //  *
    //  * @param array $resp
    //  * @return array
    //  */
    // protected function buildResponseData(string $url, array $data, array $resp)
    // {
        
    //     if (!isset($resp['code'])) {
    //         return $resp;
    //     }

    //     if (in_array($resp['code'], $this->errCode)) {
    //         preg_match("/\[(.*?)\]/", $resp['message'], $matches);
    //         $fields = explode(',', $matches[1]);
    //         $name = explode("/", $url)[0];
    //         $this->cache->set($name, $fields);
    //         $data['fields'] = $fields;
    //         $resp = $this->get($url, $data);
    //     }

    //     return json_decode(json_encode($resp), true);
    // }

    /**
     * 设置需要处理的code，避免它再加
     *
     * @param array $codes
     * @return void
     */
    public function setErrCode(array $codes = [])
    {
        $this->errCode = $codes;
        return $this;
    }

    /**
     * getErrCode
     *
     * @return array
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * getInstance
     *
     * @return Request
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * getClient
     *
     * @return Curl
     */
    public static function getClient()
    {
        if (!self::$client) {
            self::$client = new Curl();
        }
        return self::$client;
    }
}
