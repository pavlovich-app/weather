<?php
namespace App\Weather\Controller;

use App\Weather\Service\WeatherService;
use App\Weather\Source\OpenWeatherMapSource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(WeatherService $weatherService, OpenWeatherMapSource $source): Response
    {
        $cities = [];

        return $this->render('weather/default.html.twig', [
            'cities' => $cities
        ]);
    }


    #[Route('/{city}', name: 'city')]
    public function city(string $city, WeatherService $weatherService, OpenWeatherMapSource $source): Response
    {
        $weather = $weatherService->getWeatherForCity($city, $source);

        return $this->render('weather/index.html.twig', [
            'weather' => $weather,
            'city' => $city
        ]);
    }
}