<?php

namespace LanHai\TencentAds\Client;

use GuzzleHttp\Client;
use LanHai\TencentAds\Interfaces\ClientInterface;

class AsyncClient implements ClientInterface
{

    /**
     * instance
     *
     * @var AsyncClient
     */
    protected static $instance;

    /**
     * @var Client
     */
    protected $client;

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
        $response = $client->get($url.'?'.http_build_query($data));
        $this->response = $response->getBody()->getContents();
        return $this;
    }
    public function post(string $url, array $data)
    {
        $instance = $this->getDefaultClient();
        $client = $instance->getClient();
        $response = $client->request('POST', $url, [
            'form_params' => $data
        ]);
        $this->response = $response->getBody()->getContents();
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
     * @return Client
     */
    public static function getClient()
    {
        return self::$instance->client;
    }

    public static function setClient(Client $client)
    {
        self::$instance->client = $client;
        return self::$instance;
    }
}
