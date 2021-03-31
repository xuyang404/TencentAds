<?php

namespace LanHai\TencentAds;


use LanHai\TencentAds\Cache\FileCache;
use LanHai\TencentAds\Interfaces\CacheInterface;
use LanHai\TencentAds\Interfaces\ClientInterface;

/**
 * Class Request
 * @package LanHai\TencentAds
 * @method Request async()
 * @method Request curl()
 */

class Request
{

    /**
     * $instance
     *
     * @var Request
     */
    protected static $instance;

    /**
     * $request
     *
     * @var ClientInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $host = "https://api.e.qq.com/v1.1";

    /**
     * @var array
     */
    protected $apiKeys = [];

    /**
     * @var CacheInterface
     */
    protected $cache;


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
        foreach (Providers::$providers as $provider) {
            $provider::register();
        }

        $instance = self::getInstance();
        $instance->cache = FileCache::getDefaultCache();
        $instance->setApiKeys($config);
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
        $this->host = $host;
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
        $host = $this->getHost();
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
        $host = $this->getHost();
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
            $data['fields'] = $fields;
        }

        if ($action == 'post') {
            $params = $this->getApiKeys();
            foreach ($params as $key => $value) {
                if (!isset($data['query'][$key])) {
                    $data['query'][$key] = $value;
                }
            }
            $data['query']['nonce'] = uniqid(time() . rand(0, 1000));
            $data['query']['timestamp'] = time();
            $data['query'] = http_build_query($data['query']);
        }else{
            $params = $this->getApiKeys();
            foreach ($params as $key => $value) {
                if (!isset($data[$key])) {
                    $data[$key] = $value;
                }
            }
            $data['nonce'] = uniqid(time() . rand(0, 1000));
            $data['timestamp'] = time();
        }

        return $data;
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
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getApiKeys()
    {
        return $this->apiKeys;
    }

    /**
     * @param array $apiKeys
     */
    public function setApiKeys($apiKeys)
    {
        $this->apiKeys = $apiKeys;
    }

    /**
     * @return ClientInterface
     */
    public function getRequest(): ClientInterface
    {
        return $this->request;
    }

    /**
     * @param ClientInterface $request
     */
    public function setRequest(ClientInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param $method
     * @param $args
     * @return $this
     */
    public function __call($method, $args) :Request {
        $this->request = Container::make($method, $args);
        return $this;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }
}
