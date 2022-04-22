<?php

namespace App\Command;

use App\Manager\MEAPIManager;
use App\Manager\CollectionTrackerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveTrackCollectionCommand extends Command
{
    private $collectionTrackerManager;

    public function __construct(CollectionTrackerManager $collectionTrackerManager)
    {
        $this->collectionTrackerManager = $collectionTrackerManager;
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
        $this->collectionTrackerManager->removeCollection($collection);
        return 1;
    }
}