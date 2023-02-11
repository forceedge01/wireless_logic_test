<?php

declare(strict_types = 1);

namespace WirelessLogic\App\Command;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use WirelessLogic\App\Service\ProductService;
use WirelessLogic\App\Collection\Collection;

/**
 * Responsible for scraping the site configured in the parameters.yml file.
 */
class ScrapeCommand extends SymfonyCommand
{
    public function __construct(
        private ProductService $service
    ) {
        parent::__construct();
    }

    /**
     * Configure the command.
     */
    protected function configure(): void
    {
        $this->setName('scrape:items');
        $this->setDescription('Scrape web items based on the configuration fed');
    }

    /**
     * Perform the execution of the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $productCollection = $this->service->fetchData();
            $this->service->sortByYearlyPrice($productCollection, ProductService::ORDER_DESC);

            $output->writeln($this->service->generateJsonOutput($productCollection));
        } catch (Exception $e) {
            $output->writeln(sprintf('<error>Unable to execute command, error: %s</error>', $e->getMessage()));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
