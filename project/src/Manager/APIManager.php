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

class APIManager
{
    private $client;
    private $collectionTrackRepository;
    private $em;
    public function __construct(HttpClientInterface $client,
                                CollectionTrackRepository $collectionTrackRepository,
                                EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->collectionTrackRepository = $collectionTrackRepository;
        $this->em = $em;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function checkFloor(int $all = 1) {
        $collections = $this->collectionTrackRepository->getAllCollection();

        $floorPrice = 0;
        foreach ($collections as $collection) {
            $collectionData = $this->callAPI($collection->getName());
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
    public function addCollection(string $name, float $value): bool {
        if (!$this->callAPI($name)) {
            return false;
        }

        $collection = $this->em->getRepository(CollectionTrack::class)->findOneBy(['name' => $name]);
        if ($collection) {
            $collection->setValue($value);
            $this->em->persist($collection);
            $this->em->flush();
        } else {
            $collectionTrack = new CollectionTrack();
            $collectionTrack->setName($name);
            $collectionTrack->setValue($value);

            $this->collectionTrackRepository->add($collectionTrack);
        }
        return true;
    }

    public function removeCollection(string $collection) {
        $collection = $this->em->getRepository(CollectionTrack::class)->findOneBy(['name' => $collection]);
        if (!$collection) {
            dump('no collection found');
        }
        $this->collectionTrackRepository->remove($collection);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCollectionsFloor(string $collection, float $value = null) {
        $result = $this->callAPI($collection);
        dump($result);
    }
}