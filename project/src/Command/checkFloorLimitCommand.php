<?php

namespace App\Command;

use App\Manager\FeesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class checkFloorLimitCommand extends Command
{
    private $feesManager;
    public function __construct(FeesManager $feesManager)
    {
        parent::__construct();
        $this->feesManager = $feesManager;
    }

    protected function configure()
    {
        $this
            ->setName('floorAlert')
            ->setDescription('Alert on the floor price');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (42) {
            $collections = $this->feesManager->checkFloorPrice();
            foreach ($collections as $collection){
                $output->writeln("<fg=red>--- WARNING ".date('H:i')." ---</>");
                $output->writeln("name:".$collection["name"]);
                $output->writeln("limit:".$collection["limit"]);
                $output->writeln("floorPrice:".$collection["floorPrice"]);
                $output->writeln("<fg=red>--- END ---</>");
                $output->writeln("");
            }
            sleep(300);
        }
        return 1;
    }
}