<?php

namespace Jt\Amap;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class Client
{

    private $endpoint = 'restapi.amap.com';

    private $protocol = 'https';

    private $apiVersion;

    private $httpClient;

    private $lastResponse;

    public function __construct($config = [])
    {
        if (!is_array($config)) {
            throw new AmapException('The config Must be an array');
        }

        if (!isset($config['key'])) {
            throw new AmapException('Amap key Must be setting!');

        } elseif (!isset($config['api_version']) or !$config['api_version']) {
            $this->apiVersion = 'v3';
        }

        $this->key = $config['key'];

        $this->httpClient = new GuzzleClient(array_get($config, 'request_opts', []));

        // register logger.
        if (isset($config['log']['file']) && $config['log']['file']) {
            $this->registerLogger(config('amap.log'));
        }

    }

    //目前只实现了坐标转换API
    public function api($name)
    {
        switch ($name) {
            case 'coordinate':
                $api = new Api\Coordinate($this->key, $this);
                break;
            default:
                throw new InvalidArgumentException(sprintf("api [%s] is not found!", $name));
        }
        return $api;
    }

    public function version($version)
    {
        $this->apiVersion = $version;

        return $this;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function __call($method, $args)
    {
        try {
            return $this->api($method);
        } catch (InvalidArgumentException $e) {
            throw new AmapException(sprintf("Undefined method called: [%s]", $method));
        }
    }

    public function rawCall($method, $path, $content = null)
    {
        $url = $path;
        if (strpos($path, '//') === 0) {
            $url = $this->protocol . ":" . $path;
        } elseif (strpos($url, 'http') !== 0) {
            $url = $this->protocol . '://' . $this->endpoint . "/" . $this->apiVersion . "/" . $path;
        }

        $options['query'] = $content;

        // 暂时不支持post请求，需要的时候在添加。
        Log::info(sprintf("Amap: [%s] - %s", $method, $url), [$content]);

        try {
            $this->lastResponse = $response = $this->httpClient->request($method, $url, $options);
            if ($response->getHeader('Content-Type')
                && strpos($response->getHeader('Content-Type')[0], 'application/json') === 0) {
                $json = json_decode($response->getBody(), true);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new AmapException('Error parsing JSON response');
                }
                if (isset($json['status']) && $json['status']) {
                    return $json;
                }
                $msg = array_get($json, 'infocode', array_get($json, 'info'));
                throw new AmapException(sprintf("Amap Reqeust Failed With Msg: [%s]", $msg));
            } elseif ($response->getHeader('Content-Type')
                && strpos($response->getHeader('Content-Type')[0], 'application/xml') === 0) {
                $json = json_decode(json_encode(simplexml_load_string($response->getBody())), true);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new AmapException('Error parsing JSON response');
                }
                if (isset($json['status']) && $json['status']) {
                    return $json;
                }
                $msg = array_get($json, 'infocode', array_get($json, 'info'));
                throw new AmapException(sprintf("Amap Reqeust Failed With Msg: [%s]", $msg));
            }
        } catch (RequestException $e) {
            if (!$e->getResponse()) {
                throw $e;
            }
            // 以防万一
            $json = json_decode($e->getResponse()->getBody(), true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new AmapException('Error parsing JSON response');
            }
            if (isset($json['infocode']) or isset($json['info'])) {
                $msg = array_get($json, 'infocode', array_get($json, 'info'));
                throw new AmapException(sprintf("Amap Reqeust Failed With Msg: [%s]", $msg));
            }
            throw $e;
        }
    }

    protected function registerLogger($logConfig)
    {
        $level   = isset($logConfig['level']) && $logConfig['level'] ? $logConfig['level'] : 'warning';
        $daily   = isset($logConfig['daily']) && $logConfig['daily'] ? $logConfig['daily'] : false;
        $maxfile = isset($logConfig['max_file']) && $logConfig['max_file'] ? $logConfig['max_file'] : 30;
        $logger  = Log::createLogger($logConfig['file'], 'jt.amap', $level, $daily, $maxfile);
        Log::setLogger($logger);
    }
}
