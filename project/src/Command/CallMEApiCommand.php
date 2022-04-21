<?php

namespace App\Command;

use App\Manager\APIManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallMEApiCommand extends Command
{
    private $apiManager;
    public function __construct(APIManager $apiManager)
    {
        $this->apiManager = $apiManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('callAPI')
            ->setDescription('Creates a new user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $collection = "project_tenjin";
        $res = $this->apiManager->checkFloor($collection);
        $output->writeln($res);
    }
}