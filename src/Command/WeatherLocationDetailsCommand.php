<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location-details',
    description: 'Get measurements by country code and city name, arguments = [arg1 = countryCode, arg2 = cityName]',
)]
class WeatherLocationDetailsCommand extends Command
{
    public function __construct(private WeatherUtil $weatherUtil)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Country Code')
            ->addArgument('arg2', InputArgument::OPTIONAL, 'City name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $code = $input->getArgument('arg1');
        $city = $input->getArgument('arg2');

        if ($code && $city) {
            $measurements = $this->weatherUtil->getWeatherForCountryAndCity($code, $city);

            $io->writeln(sprintf('Country Code: %s, City Name: %s', $code, $city));

            foreach ($measurements as $measurement) {
                $io->writeln(sprintf("\t%s: %s",
                    $measurement->getDate()->format('Y-m-d'),
                    $measurement->getTemperature(),
                ));
            }
        }

        return Command::SUCCESS;
    }
}

