<?php


namespace Silex\Provider;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\XcacheCache;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class CacheServiceProvider
 * @package Silex\Provider
 */
class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container["cache.factory.array"] = $container->factory(function (Container $container) {
            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new ArrayCache();
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.apc"] = $container->factory(function (Container $container) {
            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new ApcCache();
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.xcache"] = $container->factory(function (Container $container) {
            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new XcacheCache();
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.redis"] = $container->factory(function (Container $container) {
            if (empty($container["cache.options"]["host"]) || empty($container["cache.options"]["port"])) {
                throw new \InvalidArgumentException("host and port need to be specified for redis cache");
            }

            $redis = new \Redis();
            $redis->connect($container["cache.options"]["host"], $container["cache.options"]["port"]);

            if (isset($container["cache.options"]["password"])) {
                $redis->auth($container["cache.options"]["password"]);
            }

            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new RedisCache();
            $cache->setRedis($redis);
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.memcached"] = $container->factory(function (Container $container) {
            if (empty($container["cache.options"]["host"]) || empty($container["cache.options"]["port"])) {
                throw new \InvalidArgumentException("host and port need to be specified for memcached cache");
            }

            $memcached = new \Memcached();
            $memcached->addServer($container["cache.options"]["host"], $container["cache.options"]["port"]);

            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new MemcachedCache();
            $cache->setMemcached($memcached);
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.memcache"] = $container->factory(function (Container $container) {
            if (empty($container["cache.options"]["host"]) || empty($container["cache.options"]["port"])) {
                throw new \InvalidArgumentException("host and port need to be specified for memcached cache");
            }

            $memcache = new \Memcache();
            $memcache->addserver($container["cache.options"]["host"], $container["cache.options"]["port"]);

            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new MemcacheCache();
            $cache->setMemcache($memcache);
            $cache->setNamespace($namespace);

            return $cache;
        });

        $container["cache.factory.filesystem"] = $container->factory(function (Container $container) {
            if (empty($container["cache.options"]["directory"])) {
                throw new \InvalidArgumentException("directory need to be specified for filesystem cache");
            }

            $namespace = empty($container["cache.options"]["namespace"]) ?
                "jowy" : $container["cache.options"]["namespace"];

            $cache = new FilesystemCache($container["cache.options"]["directory"]);
            $cache->setNamespace($namespace);

            return $cache;

        });

        $container["cache.factory"] = function (Container $container) {
            $service_id = "cache.factory." . $container["cache.driver"];

            if (!$container->offsetExists($service_id)) {
                throw new \InvalidArgumentException("invalid cache driver");
            }

            return $container[$service_id];
        };
    }
}
