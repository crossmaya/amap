<?php

namespace Jt\Amap;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Log
{
    protected static $logger;

    public static function __callStatic($method, $args)
    {
        return forward_static_call_array([self::getLogger(), $method], $args);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    public static function getLogger()
    {
        return self::$logger ?: self::$logger = self::createLogger();
    }

    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public static function hasLogger()
    {
        return self::$logger ? true : false;
    }

    public static function createLogger($file = null, $identify = null, $level = Logger::DEBUG, $type = 'daily', $max_files = 30)
    {
        $file = is_null($file) ? sys_get_temp_dir() . '/logs/amap.log' : $file;

        $handler = $type === 'single' ? new StreamHandler($file, $level) : new RotatingFileHandler($file, $max_files, $level);

        $handler->setFormatter(
            new LineFormatter("%datetime% > %level_name% > %message% %context% %extra%\n\n", null, false, true)
        );

        $logger = new Logger(is_null($identify) ? 'jt.amap' : $identify);

        $logger->pushHandler($handler);

        return $logger;
    }
}
