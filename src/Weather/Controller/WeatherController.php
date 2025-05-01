<?php
namespace App\Weather\Controller;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Service\WeatherService;
use App\Weather\Source\OpenWeatherMapSource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('weather/index.html.twig', [
            'cities' => WeatherDTO::AVAILABLE_CITIES
        ]);
    }

    #[Route('/{city}', name: 'city')]
    public function city(string $city, WeatherService $weatherService, OpenWeatherMapSource $source): Response
    {
        $weather = $weatherService->getWeatherForCity($city, $source);

        return $this->render('weather/city.html.twig', [
            'weather' => $weather,
        ]);
    }
}