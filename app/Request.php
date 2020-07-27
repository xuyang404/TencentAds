<?php

namespace LanHai\TencentAds;

use Curl\Curl;
use GuzzleHttp\Client;
use LanHai\TencentAds\Cache\FileCache;
use LanHai\TencentAds\Client\AsyncClient;
use LanHai\TencentAds\Interfaces\ClientInterface;

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
     * @var ClientInterface
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
     * @return array
     */
    public function get(string $url, array $data = [])
    {
        $data = $this->buildRequestData($url, $data);
        $host = $this->config->getHost();
        $this->request->get($host . '/' . $url, $data);
        $resp = $this->request->getResponse();
        if (is_string($resp)) {
            return json_decode($resp, true);
        }
        return json_decode(json_encode($resp), true);
    }

    public function post(string $url, array $data = [])
    {   
        $data = $this->buildRequestData($url, $data, 'post');
        $query = $data['query'];
        unset($data['query']);
        $host = $this->config->getHost();
        $this->request->post($host . '/' . $url.'?'.$query, $data);
        $resp = $this->request->getResponse();
        if (is_string($resp)) {
            return json_decode($resp, true);
        }
        return json_decode(json_encode($resp), true);
    }

    /**
     * getResponse
     *
     * @return void
     */
    public function getResponse()
    {
        return $this->request->getResponse();
    }

    /**
     * 使用协程请求客户端
     *
     * @return Request
     */
    public function async(Client $client) :Request
    {
        $this->request = AsyncClient::getDefaultClient()::setClient($client);
        return $this;
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
    public function setConfig(Config $config) :Request
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
    public function setCache($cache) :Request
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
    protected function buildRequestData(string $url, array $data, $action = 'get')
    {
        if (!isset($data['fields']) && ($action == 'get')) {
            $name = explode("/", $url)[0];
            $fields = $this->cache->get($name);        
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

    /**
     * 设置需要处理的code，避免它再加
     *
     * @param array $codes
     * @return void
     */
    public function setErrCode(array $codes = []) :Request
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

    public function setClient($client) :Request
    {
       $this->request = $client;
       return $this;
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
