<?php
namespace App\Weather\DTO;

class WeatherDTO
{
    public function __construct(
        public string $city,
        public string $country,
        public float $temperature,
        public string $description,
        public float $humidity,
        public float $windSpeed,
        public float $lastUpdate
    ) {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}