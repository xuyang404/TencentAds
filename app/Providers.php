<?php


namespace LanHai\TencentAds;

use LanHai\TencentAds\Provider\AsyncProvider;
use LanHai\TencentAds\Provider\CurlProvider;

class Providers
{
    static $providers = [
        AsyncProvider::class,
        CurlProvider::class
    ];
}