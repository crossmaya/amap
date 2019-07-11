<?php

namespace Jt\Amap\Api;

use Jt\Amap\AmapException;
use Jt\Amap\Log;

class Coordinate extends Base
{
    public function convert(array $parameters)
    {
        $this->client->version('v3');

        $defaults = [
            'key'       => $this->key,
            'locations' => '',
            'coordsys'  => 'gps',
            'sig'       => '',
            'output'    => 'JSON',
        ];

        foreach ($parameters as $key => $value) {
            if (!array_key_exists($key, $defaults)) {
                unset($parameters[$key]);
            }
        }

        $parameters = array_merge($defaults, $parameters);

        if (!$parameters['key'] or !$parameters['locations']) {
            throw new AmapException('key or locations Must be required');
        }

        $locations = explode("|", $parameters['locations']);

        if (count($locations) > 40) {
            throw new AmapException('locations point more than 40');
        }

        Log::debug(sprintf('request convert api at %s', date("Y-m-d H:i:s")));

        unset($locations);

        return $this->get('assistant/coordinate/convert', $parameters);
    }
}
