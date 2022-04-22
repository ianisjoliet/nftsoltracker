<?php

namespace App\Manager;

use App\Entity\CollectionTrack;
use App\Repository\CollectionTrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CollectionTrackerManager
{
    private $collectionTrackRepository;
    private $em;
    private $royaltiesManager;
    private $apiManager;
    public function __construct(CollectionTrackRepository $collectionTrackRepository,
                                EntityManagerInterface    $em, FeesManager $royaltiesManager, MEAPIManager $apiManager)
    {
        $this->collectionTrackRepository = $collectionTrackRepository;
        $this->em = $em;
        $this->royaltiesManager = $royaltiesManager;
        $this->apiManager = $apiManager;
    }

    public function getCollection(string $name = null){
        if (!$name) {
            return $this->collectionTrackRepository->findAll();
        } else {
            return $this->collectionTrackRepository->findOneBy(['name' => $name]);
        }
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function addCollection(string $name, float $value, float $fees): bool {
        $content = $this->apiManager->getCollectionStat($name);

        if (!$content = $this->apiManager->getCollectionStat($name)) {
            return false;
        }
        $floorLimit = $this->royaltiesManager->CalcFloorPrice("buy",$fees, $value);
        $collection = $this->em->getRepository(CollectionTrack::class)->findOneBy(['name' => $name]);
        if ($collection) {
            $collection->setValue($value);
            $collection->setFloorLimit($floorLimit);
            $this->em->persist($collection);
            $this->em->flush();
        } else {
            $collectionTrack = new CollectionTrack();
            $collectionTrack->setName($name);
            $collectionTrack->setValue($value);
            $collectionTrack->setFloorLimit($floorLimit);

            $this->collectionTrackRepository->add($collectionTrack);
        }
        return true;
    }

    public function removeCollection(int $id): bool
    {
        $collection = $this->em->getRepository(CollectionTrack::class)->findOneBy(['id' => $id]);
        if (!$collection) {
            return false;
        }
        $this->collectionTrackRepository->remove($collection);
        return true;
    }
}