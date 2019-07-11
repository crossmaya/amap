# Amap Client for Laravel 5

**高德地图API**

说明：本项目为试验性项目，目前不能用于生产环境

## Installation

试验性项目，待补充。

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

试验性项目，待补充。

## Configuration

试验性项目，待补充。

