<?php
namespace App\Weather\Command;

use App\Weather\Service\WeatherService;
use App\Weather\Source\OpenWeatherMapSource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:weather:fetch',
    description: 'Fetch weather info for a given city'
)]
class WeatherFetchCommand extends Command
{
    public function __construct(
        private WeatherService $weatherService,
        private OpenWeatherMapSource $openWeatherMapSource)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('city', InputArgument::REQUIRED, 'City to fetch weather for');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $city = $input->getArgument('city');
        $data = $this->weatherService->fetchWeatherForCity($city, $this->openWeatherMapSource);

        if (!$data) {
            $output->writeln('<error>' . 'Cant receive Weather' . '</error>');
            return Command::FAILURE;
        }

        $this->weatherService->setWeatherToCache($this->openWeatherMapSource->getCacheKey($city), $data);

        $output->writeln("Weather in {$city}: {$data->temperature}°C");

        return Command::SUCCESS;
    }
}