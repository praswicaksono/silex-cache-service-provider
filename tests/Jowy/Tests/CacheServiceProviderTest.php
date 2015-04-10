<?php


namespace Jowy\Tests;

use Doctrine\Common\Cache\Cache;
use Pimple\Container;
use Silex\Provider\CacheServiceProvider;

class CacheServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    /**
     * test php array cache
     */
    public function testPhpArrayCache()
    {
        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "array",
            "cache.options" => [
                "namespace" => "Test"
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test apc cached
     */
    public function testApcCache()
    {
        if (! extension_loaded("apc") || false === @apc_cache_info()) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of APC');
        }

        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "apc",
            "cache.options" => [
                "namespace" => "Test"
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test xcache cache
     */
    public function testXcacheCache()
    {
        if (! extension_loaded("xcache")) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of xcache');
        }

        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "xcache",
            "cache.options" => [
                "namespace" => "Test"
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test filesystem cache
     */
    public function testFilesystemCache()
    {
        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "filesystem",
            "cache.options" => [
                "namespace" => "Test",
                "directory" => sys_get_temp_dir() . "/jowy_cache_" . uniqid()
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test redis cache
     */
    public function testRedisCache()
    {
        if (! extension_loaded("redis")) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of redis');
        }

        $redis = new \Redis();

        $ok = @$redis->connect("127.0.0.1");
        if (! $ok) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of redis');
        }

        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "redis",
            "cache.options" => [
                "namespace" => "Test",
                "host" => "127.0.0.1",
                "port" => 6379
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test memcached cache
     */
    public function testMemcacheTest()
    {
        if (! extension_loaded("memcache")) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of memcache');
        }

        $memcache = new \Memcache();

        if (@$memcache->connect('localhost', 11211) === false) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of memcache');
        }

        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "memcache",
            "cache.options" => [
                "namespace" => "Test",
                "host" => "127.0.0.1",
                "port" => 11211
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * test memcached cache
     */
    public function testMemcachedCache()
    {
        if (! extension_loaded("memcache")) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of memcached');
        }

        if (@fsockopen("127.0.0.1", 11211) === false) {
            $this->markTestSkipped('The ' . __METHOD__ .' requires the use of memcached');
        }

        $this->container->register(new CacheServiceProvider(), [
            "cache.driver" => "memcached",
            "cache.options" => [
                "namespace" => "Test",
                "host" => "127.0.0.1",
                "port" => 11211
            ]
        ]);

        $cache = $this->container["cache.factory"];

        $this->assertInstanceOf(Cache::class, $cache);
    }
}
