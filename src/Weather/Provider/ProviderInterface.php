<?php
namespace App\Weather\Provider;

use App\Weather\DTO\WeatherDTO;

interface ProviderInterface
{
    /**
     * @param string $city
     * @return WeatherDTO|null
     */
    public function getWeather(string $city): ?WeatherDTO;

    /**
     * @param string $city
     * @return string]
     */
    public function getCacheKey(string $city): string;
}