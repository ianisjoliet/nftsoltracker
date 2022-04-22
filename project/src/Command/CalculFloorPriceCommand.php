<?php

namespace App\Command;

use App\Manager\FeesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculFloorPriceCommand extends Command
{
    private $feesManager;

    public function __construct(FeesManager $feesManager)
    {
        $this->feesManager= $feesManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('calcFloorPrice')
            ->setDescription('Check Floor Price')
            ->addArgument('BuySell', InputArgument::REQUIRED, 'Buy or Sell.')
            ->addArgument('royalties', InputArgument::REQUIRED, 'royalties value.')
            ->addArgument('price', InputArgument::REQUIRED, 'NFT Last Price.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buySell = $input->getArgument('BuySell');
        $royalties = $input->getArgument('royalties');
        $nftPrice = $input->getArgument('price');
        $lastPrice = $this->feesManager->CalcFloorPrice($buySell, $royalties, $nftPrice);
        $output->writeln($lastPrice);
        return 1;
    }

}