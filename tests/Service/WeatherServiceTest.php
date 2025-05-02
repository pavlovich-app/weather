<?php

namespace App\Tests\Service;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Provider\SourceInterface;
use App\Weather\Service\CacheService;
use App\Weather\Service\WeatherService;
use PHPUnit\Framework\TestCase;

class WeatherServiceTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFetchWeatherForCityReturnsWeatherDTO()
    {
        $mockDTO = $this->createMock(WeatherDTO::class);

        $mockSource = $this->createMock(SourceInterface::class);
        $mockSource->expects($this->once())
            ->method('getWeather')
            ->with('London')
            ->willReturn($mockDTO);

        $mockCache = $this->createMock(CacheService::class);

        $service = new WeatherService($mockCache);

        $result = $service->fetchWeatherForCity('London', $mockSource);
        $this->assertSame($mockDTO, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetWeatherForCityReturnsNullWhenCacheEmpty()
    {
        $mockCache = $this->createMock(CacheService::class);
        $mockCache->method('get')->willReturn(null);

        $mockSource = $this->createMock(SourceInterface::class);
        $mockSource->method('getCacheKey')->willReturn('key');

        $service = new WeatherService($mockCache);

        $this->assertNull($service->getWeatherForCity('London', $mockSource));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetWeatherForCityReturnsDTO()
    {
        $expectedArray = [
            'city' => 'London',
            'country' => 'GB',
            'temperature' => 10,
            'temperature_min' => 5,
            'temperature_max' => 15,
            'pressure' => 1013,
            'description' => 'cloudy',
            'humidity' => 80,
            'wind' => 3.5,
            'timestamp' => 1234567890,
        ];

        $mockCache = $this->createMock(CacheService::class);
        $mockCache->method('get')->willReturn($expectedArray);

        $mockSource = $this->createMock(SourceInterface::class);
        $mockSource->method('getCacheKey')->willReturn('key');

        $service = new WeatherService($mockCache);
        $result = $service->getWeatherForCity('London', $mockSource);

        $this->assertInstanceOf(WeatherDTO::class, $result);
        $this->assertEquals('London', $result->city);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testSetWeatherToCacheCallsCacheWithCorrectData()
    {
        $dto = new WeatherDTO(
            'London', 'GB', 10, 5, 15, 1013, 'clear', 70, 3.5, 1234567890
        );

        $mockCache = $this->createMock(CacheService::class);
        $mockCache->expects($this->once())
            ->method('set')
            ->with('London', $dto->toArray());

        $service = new WeatherService($mockCache);
        $service->setWeatherToCache('London', $dto);
    }
}
