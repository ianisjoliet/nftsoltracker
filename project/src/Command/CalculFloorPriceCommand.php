<?php

namespace App\Command;

use App\Manager\RoyaltiesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculFloorPriceCommand extends Command
{
    private $royaltiesManager;

    public function __construct(RoyaltiesManager $royaltiesManager)
    {
        $this->royaltiesManager= $royaltiesManager;
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
        $lastPrice = $this->royaltiesManager->CalcFloorPrice($buySell, $royalties, $nftPrice);
        dump($lastPrice);
        return 1;
    }

}