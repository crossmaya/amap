<?php

namespace Jt\Amap\Api;

use Jt\Amap\AmapException;
use Jt\Amap\Log;

class Weather extends Base
{
    public function weatherInfo($city, $type = 'base', $format = 'json')
    {
        if (!in_array(strtolower($format), ['xml', 'json'])) {
            throw new AmapException('Invalid response format: ' . $format);
        }
        if (!in_array(strtolower($type), ['base', 'all'])) {
            throw new AmapException('Invalid type value(base/all): ' . $type);
        }

        $format     = strtolower($format);
        $type       = strtolower($type);
        $parameters = array_filter([
            'key'        => $this->client->getKey(),
            'city'       => $city,
            'output'     => $format,
            'extensions' => $type,
        ]);

        Log::debug(sprintf('request weatherInfo api at %s', date("Y-m-d H:i:s")));

        return $this->get('weather/weatherInfo', $parameters);
    }
}
