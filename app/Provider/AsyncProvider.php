<?php


namespace LanHai\TencentAds\Provider;


use LanHai\TencentAds\Client\AsyncClient;
use LanHai\TencentAds\Container;
use LanHai\TencentAds\Interfaces\ProviderInterface;

class AsyncProvider implements ProviderInterface
{

    public static function register()
    {
        Container::bind('async', function () {
            return AsyncClient::getDefaultClient();
        });
    }
}