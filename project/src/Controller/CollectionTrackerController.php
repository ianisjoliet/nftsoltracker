<?php

namespace App\Controller;

use App\Entity\CollectionTrack;
use App\Manager\FeesManager;
use App\Manager\MEAPIManager;
use App\Manager\CollectionTrackerManager;
use App\Repository\CollectionTrackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/tracker");
 */
class CollectionTrackerController
{
    /**
     * @Route(name="Add collection", methods={"POST"})
     * @param Request $request
     * @param CollectionTrackRepository $collectionTrackRepository
     * @param SerializerInterface $serializer
     * @param CollectionTrackerManager $collectionTrackerManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function addCollectionTracker(Request $request, CollectionTrackRepository $collectionTrackRepository,
                                         SerializerInterface $serializer,
                                         CollectionTrackerManager $collectionTrackerManager

    ): Response
    {
        $result = json_decode($request->getContent(), false);

        if (!$result->collectionName || !$result->price || !$result->fees) {
            throw new BadRequestHttpException('name | value | fees arguments required');
        }

        if (!$collectionTrackerManager->addCollection($result->collectionName, $result->price, $result->fees)) {
            throw new NotFoundHttpException();
        }

        return new Response();
    }

    /**
     * @Route("/{id}", name="Remove collection", methods={"DELETE"})
     * @param CollectionTrack $collectionTrack
     * @param SerializerInterface $serializer
     * @param CollectionTrackerManager $collectionTrackerManager
     * @return Response
     */
    public function removeCollectionTracker(CollectionTrack $collectionTrack,
                                            SerializerInterface $serializer,
                                            CollectionTrackerManager $collectionTrackerManager): Response
    {

        if (!$collectionTrackerManager->removeCollection($collectionTrack->getId())) {
            return new Response(
                $serializer->serialize("unable to remove collection:".$collectionTrack->getName(), "json")
            );
        }
        return new Response();
    }

    /**
     * @Route("/floor_limit", name="Get all collection", methods={"POST"})
     * @param Request $request
     * @param FeesManager $feesManager
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function calcFloorLimit(Request $request, FeesManager $feesManager,SerializerInterface $serializer): Response
    {
        $result = json_decode($request->getContent(), false);

        if (!$result->price || !$result->fees || !$result->buySell) {
            throw new BadRequestHttpException('name|value|fees arguments required');
        }

        $floorPrice = $feesManager->CalcFloorPrice($result->buySell, $result->fees, $result->price);
        return new Response(
            $serializer->serialize($floorPrice, "json")
        );
    }

    /**
     * @Route("/collections/all", name="Get all collection", methods={"GET"})
     */
    public function getAllCollectionTracker(Request $request,
                                            CollectionTrackerManager $collectionTrackerManager,
                                            SerializerInterface $serializer): Response
    {
        $collectionList = $collectionTrackerManager->getCollection();
        return new Response(
            $serializer->serialize($collectionList, "json")
        );
    }
}

/* return new Response(
     $serializer->serialize($result->name, "json")
 );*/