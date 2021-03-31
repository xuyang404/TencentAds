<?php

use LanHai\TencentAds\Request;

require_once dirname(__FILE__) . "/vendor/autoload.php";


$data = file_get_contents('config.json');
$config = json_decode($data, true);
for ($i=0; $i < 10; $i++) {
    Swoole\Coroutine::create(function () use ($config)
    {
        $request = Request::init([
            'access_token' => $config['access_token']
        ])->async();
        $resp = $request->get('ads/get', [
            'account_id' => $config['account_id'],
        ]);
        var_dump($resp);
    });
}
