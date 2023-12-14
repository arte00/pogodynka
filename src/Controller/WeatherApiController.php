<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('api/v1/weather/', name: 'app_weather_api', methods: ['GET'])]

    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $countryCode,
        #[MapQueryParameter('city')] string $cityName,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($countryCode, $cityName);

        if ($format == 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $cityName,
                    'country' => $countryCode,
                    'measurements' => $measurements,
                ]);

            }
            $result = "country,city,date,temperature,windSpeed,humidity,pressure\n";

            foreach ($measurements as $measurement) {
                $result .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $cityName,
                    $countryCode,
                    $measurement->getDate()->format('Y-m-d'),
                    $measurement->getTemperature(),
                    $measurement->getTemperatureFahrenheit()
                );
            }
            return new Response($result, 200);
        } else {
            if ($twig) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $cityName,
                    'country' => $countryCode,
                    'measurements' => $measurements,
                ]);

            }
            return $this->json([
                'country' => $countryCode,
                'city' => $cityName,
                'measurements' => array_map(fn(Measurement $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'temperatureCelsius' => $m->getTemperature(),
                    'temperatureFahrenheit' => $m->getTemperatureFahrenheit(),
                    'windSpeed' => $m->getWindSpeed(),
                    'humidity' => $m->getHumidity(),
                    'pressure' => $m->getPressure(),
                ], $measurements),

            ]);
        }
    }
}
