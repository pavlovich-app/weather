<?php

namespace App\Weather\Service;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Provider\SourceInterface;

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
     * @param SourceInterface $source
     * @return WeatherDTO|null
     */
    public function fetchWeatherForCity(string $city, SourceInterface $source): ?WeatherDTO
    {
        return $source->getWeather($city);
    }

    /**
     * @param string $city
     * @param SourceInterface $provider
     * @return WeatherDTO|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getWeatherForCity(string $city, SourceInterface $provider): ?WeatherDTO
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