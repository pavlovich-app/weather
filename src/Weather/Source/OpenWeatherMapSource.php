<?php

namespace App\Weather\Source;

use App\Weather\DTO\WeatherDTO;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapSource implements SourceInterface
{
    const API_URL = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $weatherApiKey
    ) {}

    /**
     * @param string $city
     * @return WeatherDTO|null
     */
    public function getWeather(string $city): ?WeatherDTO
    {
        try {
            $response = $this->httpClient->request('GET', self::API_URL, [
                'query' => [
                    'q' => $city,
                    'appid' => $this->weatherApiKey,
                    'units' => 'metric'
                ]
            ]);

            $weather = $response->toArray();

            if (!$weather) {
                return null;
            }

            return new WeatherDTO(
                $weather['location']['name'],
                $weather['location']['country'],
                $weather['current']['temp_c'],
                $weather['current']['condition']['text'],
                $weather['current']['humidity'],
                $weather['current']['wind_kph'],
                $weather['current']['last_updated'],
            );
        } catch (\Throwable $e) {
            $this->logger->error('Failed to fetch weather data', ['exception' => $e]);
            return null;
        }
    }

    /**
     * @param string $city
     * @return string
     */
    public function getCacheKey(string $city): string
    {
        return "open_weather_{$city}";
    }
}