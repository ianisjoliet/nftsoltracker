<?php

namespace App\Manager;

use App\Repository\CollectionTrackRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class FeesManager
{
    private $collectionTrackRepository;
    public function __construct(CollectionTrackRepository $collectionTrackRepository)
    {
        $this->collectionTrackRepository = $collectionTrackRepository;
    }
    public function CalcFloorPrice(string $buySell, float $rt, float $price): float
    {
        if ($buySell == "buy") {
            return $price + (($rt/100)*$price);
        } else if ($buySell == "sell") {
            $marge = ($rt * $price / 100);
            return ($price - $marge);
        }
        return 0;
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function checkFloor(int $all = 1) {
        $collections = $this->collectionTrackRepository->getAllCollection();
        dump($collections);
        $floorPrice = 0;
        foreach ($collections as $collection) {
            $collectionData = $this->getCollectionStat($collection->getName());
            $floorPrice = $collectionData['floorPrice'] / 1000000000;
            if ($floorPrice <= $collection->getValue()) {
                dump("/!\--- ".$collectionData['symbol']." ...!");
                dump("FLOOR PRICE:".$floorPrice);
                dump("VALUE:".$collection->getValue());
            } else if ($all === 1){
                dump("--- ".$collectionData['symbol']." ...");
                dump("FLOOR PRICE:".$floorPrice);
                dump("VALUE:".$collection->getValue());
            }
        }
    }
}