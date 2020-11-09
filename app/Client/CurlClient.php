<?php

namespace LanHai\TencentAds\Client;

use LanHai\TencentAds\Interfaces\ClientInterface;
use Curl\Curl;

class CurlClient implements ClientInterface
{

    /**
     * instance
     *
     * @var CurlClient
     */
    protected static $instance;

    /**
     * @var Curl
     */
    protected static $client;

    /**
     * response
     */
    protected $response;

    protected function __construct()
    {
    }

    /**
     * get
     *
     * @param string $url
     * @param array $data
     * @return void
     */
    public function get(string $url, array $data)
    {   $instance = $this->getDefaultClient();
        $client = $instance->getClient();
        $this->response = $client->request->get($url, $data);
        return $this;
    }

    /**
     * post
     *
     * @param string $url
     * @param array $data
     * @return void
     */
    public function post(string $url, array $data)
    {
        $instance = $this->getDefaultClient();
        $client = $instance->getClient();
        $this->response = $client->request->post($url, $data);
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
     * @return CurlClient
     */
    public static function getDefaultClient()
    {
        if (!self::$instance) {
            self::$instance = new CurlClient();
        }
        return self::$instance;
    }

    /**
     * getCurl
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
