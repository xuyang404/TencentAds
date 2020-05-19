<?php

namespace LanHai\TencentAds\Interfaces;


interface CacheInterface {
    public function get(string $name); 
    public function set(string $name, $value); 
    public function del(string $name); 
    public static function getDefaultCache();
    
}