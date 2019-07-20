# Amap Client for Laravel 5

**高德地图API**

说明：目前不能用于生产环境

## Installation

待补充。

## Usage

坐标转换api接口：
```
Route::get('/', function() {
    $client = app('amap.client');

    dump($client->api('coordinate')->convert([
        'locations' => '116.481499,39.990475|116.481499,39.990375',
    ]));
    // or
     dump($client->coordinate()->convert([
        'locations' => '116.481499,39.990475|116.481499,39.990375',
     ]));
});
```

新增天气查询接口: $client->api('weather')->weatherInfo();

待补充。

## Configuration

待补充。

