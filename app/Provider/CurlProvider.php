<?php


namespace LanHai\TencentAds\Provider;


use LanHai\TencentAds\Client\CurlClient;
use LanHai\TencentAds\Container;
use LanHai\TencentAds\Interfaces\ProviderInterface;

class CurlProvider implements ProviderInterface
{

    public static function register()
    {
       Container::bind('curl', function () {
           return CurlClient::getDefaultClient();
       });
    }
}