<?php

namespace App\Command;

use App\Manager\CollectionTrackerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddTrackCollectionCommand extends Command
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
            ->setName('addCollection')
            ->setDescription('Add collection to track.')
            ->addArgument('collection', InputArgument::REQUIRED, 'Name of the collection.')
            ->addArgument('value', InputArgument::REQUIRED, 'Value of the limit.')
            ->addArgument('fees', InputArgument::REQUIRED, 'Value of the fees.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $input->getArgument('collection');
        $value = $input->getArgument('value');
        $fees = $input->getArgument('fees');

        if (!$this->collectionTrackerManager->addCollection($collection, $value, $fees)) {
            dump("unable to add collection:".$collection);
        }

        return 1;
    }
}