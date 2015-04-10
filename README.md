## Silex Cache Service Provider

Provide doctrine cache for silex application

### Requirement

* PHP >= 5.5
* Pimple >= 3.0
* Doctrine/Cache >= 1.4

### Installation

`composer require jowy/silex-cache-service-provider`

### Usage

~~~php

$container = new Container()
$$container->register(new CacheServiceProvider(), [
            "cache.driver" => "array|filesystem|apc|xcache|redis|memcache|memcached",
            "cache.options" => [
                "namespace" => "your-cache-namespace",
                "directory" => "set-this-if-only-you-select-filesystem-driver",
                "host" => "only-for-redis-memcached-memcache",
                "port" => "only-for-redis-memcached-memcache",
                "password" => "if-any"
            ]
        ]);

$cache = $this->container["cache.factory"];

$this->assertInstanceOf(Cache::class, $cache);

~~~

### License

MIT, see LICENSE