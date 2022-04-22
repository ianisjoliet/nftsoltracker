<?php

namespace App\Command;

use App\Manager\APIManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddTrackCollectionCommand extends Command
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
            ->setName('addCollection')
            ->setDescription('Add collection to track.')
            ->addArgument('collection', InputArgument::REQUIRED, 'Name of the collection.')
            ->addArgument('value', InputArgument::REQUIRED, 'Value of the limit.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $input->getArgument('collection');
        $value = $input->getArgument('value');
        if (!$this->apiManager->addCollection($collection, $value)) {
            dump("unable to add collection:".$collection);
        }

        return 1;
    }
}