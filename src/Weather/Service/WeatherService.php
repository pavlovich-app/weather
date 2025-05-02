<?php

namespace App\Weather\Service;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Provider\ProviderInterface;

class WeatherService
{
    /**
     * WeatherService constructor.
     * @param CacheService $cache
     */
    public function __construct(private CacheService $cache)
    {
    }

    /**
     * @param string $city
     * @param ProviderInterface $source
     * @return WeatherDTO|null
     */
    public function fetchWeatherForCity(string $city, ProviderInterface $source): ?WeatherDTO
    {
        return $source->getWeather($city);
    }

    /**
     * @param string $city
     * @param ProviderInterface $provider
     * @return WeatherDTO|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getWeatherForCity(string $city, ProviderInterface $provider): ?WeatherDTO
    {
        $cache = $this->cache->get($provider->getCacheKey($city));

        if (empty($cache)) return null;
        
        return (new WeatherDTO)->loadAttributes($cache);
    }

    /**
     * @param string $city
     * @param WeatherDTO $data
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function setWeatherToCache(string $city, WeatherDTO $data)
    {
        $this->cache->set($city, $data->toArray());
    }
}