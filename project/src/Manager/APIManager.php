<?php
namespace App\Manager;

use App\Entity\CollectionTrack;
use App\Repository\CollectionTrackRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class APIManager
{
    private $client;
    private $collectionTrackRepository;

    public function __construct(HttpClientInterface $client, CollectionTrackRepository $collectionTrackRepository)
    {
        $this->client = $client;
        $this->collectionTrackRepository = $collectionTrackRepository;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function checkFloor() {
        $collections = $this->collectionTrackRepository->getAllCollection();

        $floorPrice = 0;
        foreach ($collections as $collection) {
            $collectionData = $this->callAPI($collection->getName());
            $floorPrice = $collectionData['floorPrice'];
            if ($floorPrice <= $collection->getValue()) {
                dump("floor price low(".$collectionData['floorPrice'].") for collection:".$collectionData['symbol']);
            } else {
                dump("floor price(".$collectionData['floorPrice'].") ok for collection:".$collectionData['symbol']);
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function callAPI(string $collection): ?array {
        $response = $this->client->request(
            'GET',
            'https://api-mainnet.magiceden.dev/v2/collections/'.$collection.'/stats'
        );
        $content = $response->toArray();
        if ($response->getStatusCode() === 200 && isset($content['floorPrice']) ) {
            return $content;
        } else {
            return null;
        }
    }

    /**
     * @throws ORMException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws OptimisticLockException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function addCollection(string $name, int $value): bool {
        if (!$this->callAPI($name)) {
            return false;
        }

        $collectionTrack = new CollectionTrack();
        $collectionTrack->setName($name);
        $collectionTrack->setValue($value);

        $this->collectionTrackRepository->add($collectionTrack);
        return true;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCollectionsFloor(string $collection, int $value = null) {
        $result = $this->callAPI($collection);
        dump($result);
    }
}