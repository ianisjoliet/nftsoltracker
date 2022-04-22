<?php

namespace App\Command;

use App\Manager\MEAPIManager;
use App\Manager\FeesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallMEApiCommand extends Command
{
    private $feesManager;
    public function __construct(FeesManager $feesManager)
    {
        $this->feesManager = $feesManager;

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
        $this->feesManager->checkFloor($all);

        return 1;
    }
}