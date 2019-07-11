<?php

return [
    // api key.
    'key'          => '',

    // api version.
    'api_version'  => 'v3',

    // guzzle client options.
    'request_opts' => [
        'verify'  => false,
        'timeout' => '5.0',
    ],

    // logger options
    'log'          => [
        'file'     => storage_path('logs') . "/amap.log",
        'level'    => 'debug', //for develop.
        'daily'    => false,
        'max_file' => 30,
    ],
];
