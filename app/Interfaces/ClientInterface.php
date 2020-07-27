<?php

namespace LanHai\TencentAds\Interfaces;


use GuzzleHttp\Client;

interface ClientInterface {
    public function get(string $url, array $data); 
    public function post(string $url, array $data); 
    public function put(string $url, array $data);  
    public function delete(string $url, array $data);
    public static function getClient();
    public static function setClient(Client $client);
    public function getResponse();
}