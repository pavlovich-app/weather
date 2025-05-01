<?php
namespace App\Weather\DTO;

class WeatherDTO
{
    const AVAILABLE_CITIES = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Dallas', 'Boston'];

    public function __construct(
        public ?string $city = null,
        public ?string $country = null,
        public ?float $temperature = null,
        public ?float $temperatureMin = null,
        public ?float $temperatureMax = null,
        public ?float $pressure = null,
        public ?string $description = null,
        public ?float $humidity = null,
        public ?float $windSpeed = null,
        public ?int $lastUpdate = null
    ) {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function loadAttributes(array $data): WeatherDTO
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}