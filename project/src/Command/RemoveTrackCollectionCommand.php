<?php

namespace App\Command;

use App\Manager\APIManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveTrackCollectionCommand extends Command
{
    private $apiManager;

    public function __construct(APIManager $apiManager)
    {
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('removeCollection')
            ->setDescription('Remove collection to track.')
            ->addArgument('collection', InputArgument::REQUIRED, 'Name of the collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $input->getArgument('collection');
        $this->apiManager->removeCollection($collection);
        return 1;
    }
}