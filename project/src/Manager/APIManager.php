<?php
namespace App\Manager;

use App\Entity\CollectionTrack;
use App\Repository\CollectionTrackRepository;
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

    public function checkFloor(string $collection) {
        $collections = $this->collectionTrackRepository->getAllCollection();

        $floorPrice = 0;
        foreach ($collections as $collection) {
            $floorPrice = $this->callAPI($collection->getName());
            if ($floorPrice <= $collection->getValue()) {
                dump("floor price low");
            }
        }
    }

    public function callAPI(string $collection) {
        $response = $this->client->request(
            'GET',
            'https://api-mainnet.magiceden.dev/v2/collections/'.$collection.'/stats'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        if (isset($content['floorPrice']))
            return $content['floorPrice'];
        return 0;
    }

    public function addCollection(string $name, int $value) {
        $collectionTrack = new CollectionTrack();
        $collectionTrack->setName($name);
        $collectionTrack->setValue($value);

        $this->collectionTrackRepository->add($collectionTrack);
    }

    public function getCollectionsFloor(string $collection, int $value = null) {
        $result = $this->callAPI($collection);
        dump($result);
    }
}