<?php

namespace Jt\Amap\Facades;

use Illuminate\Support\Facades\Facade;

class Client extends Facade
{

    protected static function getFacadeAccessor() : string
    {
        return 'amap.client';
    }
}