<?php

namespace App\Manager;

use App\Entity\CollectionTrack;
use App\Repository\CollectionTrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class FeesManager
{
    private $collectionTrackRepository;
    private $meApiManager;
    private $entityManager;
    public function __construct(CollectionTrackRepository $collectionTrackRepository,
                                MEAPIManager $meApiManager, EntityManagerInterface $entityManager)
    {
        $this->collectionTrackRepository = $collectionTrackRepository;
        $this->meApiManager = $meApiManager;
        $this->entityManager = $entityManager;
    }
    public function CalcFloorPrice(string $buySell, float $rt, float $price): float
    {
        if (strtolower($buySell) == "buy") {
            return $price + (($rt/100)*$price);
        } else if (strtolower($buySell) == "sell") {
            $marge = ($rt * $price / 100);
            return ($price - $marge);
        }
        return 0;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function checkFloorPrice(): array
    {
        $collections = $this->collectionTrackRepository->findAll();
        $floorResult = [];
        $floor = [];
        foreach ($collections as $collection) {
            $collectionData = $this->meApiManager->getCollectionStat($collection->getName());
            $floorPrice = ($collectionData['floorPrice'] / 1000000000);
            $this->updateFloorPrice($collection->getName(),$floorPrice);
            $limit = $collection->getFloorLimit() + (10 * $collection->getValue() / 100);
            if ($floorPrice <= $limit) {
                $floor['name'] = $collection->getName();
                $floor['limit'] = $limit;
                $floor['floorPrice'] = $floorPrice;
                $floorResult[] = $floor;
            }
        }
        return $floorResult;
    }

    public function updateFloorPrice(string $name, float $floor) {
        $collection = $this->entityManager->getRepository(CollectionTrack::class)->findOneBy(['name' => $name]);
        $collection->setCurrentFloor($floor);
        $this->collectionTrackRepository->add($collection);
    }
}