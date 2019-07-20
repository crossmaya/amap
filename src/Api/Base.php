<?php

namespace Jt\Amap\Api;

abstract class Base
{
    public $client;

    public function __construct($client)
    {
        $this->client = $client;
    }
    
    public function get($path, $content = null)
    {
        return $this->client->rawCall("GET", $path, $content);
    }

    public function post($path, $content = null)
    {
        return $this->client->rawCall("POST", $path, $content);
    }
}
