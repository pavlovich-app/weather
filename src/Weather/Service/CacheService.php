<?php
namespace App\Weather\Service;

use Psr\Cache\CacheItemPoolInterface;

class CacheService
{
    public function __construct(private CacheItemPoolInterface $cachePool) {}

    /**
     * @param string $key
     * @return mixed|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key)
    {
        $cacheItem = $this->cachePool->getItem($key);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        return null;
    }

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, $value, int $ttl = 3600): bool
    {
        $cacheItem = $this->cachePool->getItem($key);
        $cacheItem->set($value);
        $cacheItem->expiresAfter($ttl);

        return $this->cachePool->save($cacheItem);
    }
}
