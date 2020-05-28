<?php

namespace LanHai\TencentAds\Interfaces;


interface ClientInterface {
    public function get(string $url, array $data); 
    public function post(string $url, array $data); 
    public function put(string $url, array $data);  
    public function delete(string $url, array $data);
    public static function getClient();
    public function getResponse();
}