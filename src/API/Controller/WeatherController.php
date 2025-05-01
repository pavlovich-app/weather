<?php

namespace App\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class WeatherController extends AbstractController
{
    #[Route('/{city}', name: 'api_weather_test', methods: ['GET'])]
    public function getWeather(string $city): JsonResponse
    {
        $data = [
            'city' => $city,
            'country' => 'US',
            'temperature' => 21.5,
            'description' => 'sunny',
            'humidity' => 60,
            'windSpeed' => 3.4,
            'lastUpdate' => time(),
        ];

        return $this->json($data);
    }
}
