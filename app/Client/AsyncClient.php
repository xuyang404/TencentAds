<?php

namespace LanHai\TencentAds\Client;

use LanHai\TencentAds\Interfaces\ClientInterface;
use EasySwoole\HttpClient\HttpClient;

class AsyncClient implements ClientInterface
{

    /**
     * instance
     *
     * @var AsyncClient
     */
    protected static $instance;

    /**
     * @var \EasySwoole\HttpClient\HttpClient
     */
    protected static $client;

    /**
     * response
     */
    protected $response;

    protected function __construct()
    {
    }

    public function get(string $url, array $data)
    {   $instance = $this->getDefaultClient();
        $client = $instance->getClient();
        $client->setUrl($url.'?'.http_build_query($data));
        $resp = $client->get();
        $this->response = $resp->getBody();
        return $this;;
    }
    public function post(string $url, array $data)
    {
        $instance = $this->getDefaultClient();
        $client = $instance->getClient();
        $client = $client->setUrl($url);
        $resp = $client->post($data);
        $this->response = $resp->getBody();
        return $this;
    }
    public function put(string $url, array $data)
    {
    }
    public function delete(string $url, array $data)
    {
    }

    /**
     * getResponse
     *
     * @return void
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * getDefaultClient
     *
     * @return AsyncClient
     */
    public static function getDefaultClient()
    {
        if (!self::$instance) {
            self::$instance = new AsyncClient();
        }
        return self::$instance;
    }

    /**
     * getCurl
     *
     * @return \EasySwoole\HttpClient\HttpClient
     */
    public static function getClient()
    {
        if (!self::$client) {
            self::$client = new HttpClient();
        }
        return self::$client;
    }
}
