<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Show Measurements for location; arguments = [arg1 = locationId]',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(private WeatherUtil $weatherUtil, private LocationRepository $locationRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Id of the city')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('arg1');

        if ($locationId) {
            $location = $this->locationRepository->find($locationId);
            $measurements = $this->weatherUtil->getWeatherForLocation($location);

            $io->writeln(sprintf('id: %s, Location: %s', $location->getId(), $location->getName()));

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
