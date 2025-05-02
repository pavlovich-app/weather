<?php
namespace App\Weather\Command;

use Psr\Log\LoggerInterface;
use App\Weather\DTO\WeatherDTO;
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
        private OpenWeatherMapSource $openWeatherMapSource,
        private LoggerInterface $logger)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('city', InputArgument::OPTIONAL, 'City to fetch weather for');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $city = $input->getArgument('city') ?? null;

            $cities = $city ? [$city] : WeatherDTO::AVAILABLE_CITIES;

            foreach ($cities as $city) {
                $this->logger->info("START Fetching weather for {$city}");

                $data = $this->weatherService->fetchWeatherForCity($city, $this->openWeatherMapSource);

                if (!$data) {
                    $output->writeln('<error>' . 'Cant receive Weather' . '</error>');
                    return Command::FAILURE;
                }

                $this->weatherService->setWeatherToCache($this->openWeatherMapSource->getCacheKey($city), $data);

                $output->writeln("Weather in {$city}: {$data->temperature}°C") . PHP_EOL;

                $this->logger->info("END Fetching weather for {$city}");
            }

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            $this->logger->error('Weather fetch failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}