<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{

    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: [
        'city' => '[a-zA-Z]+',
        'country' => '[A-Z]{2}'
    ])]
    public function city(string $city, string $country, LocationRepository $locationRepository, MeasurementRepository $measurementRepository): Response
    {
        $location = $locationRepository->findLocationByCodeAndCity($country, $city);

        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
