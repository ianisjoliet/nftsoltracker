<?php
namespace App\Manager;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MEAPIManager
{
    private $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCollectionStat(string $collection): ?array {
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
}