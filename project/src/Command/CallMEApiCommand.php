<?php

namespace App\Command;

use App\Manager\APIManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
            ->setDescription('Check collection Floor Price')
            ->addArgument('all', InputArgument::REQUIRED, 'Get all informations.');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $all = $input->getArgument('all');
        $this->apiManager->checkFloor($all);

        return 1;
    }
}