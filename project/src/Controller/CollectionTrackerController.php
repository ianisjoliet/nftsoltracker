<?php

namespace App\Controller;

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
     * @Route(name="get", methods={"POST"})
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

        if (!$result->name || !$result->value || !$result->fees) {
            throw new BadRequestHttpException('name|value|fees arguments required');
        }

        if (!$collectionTrackerManager->addCollection($result->name, $result->value, $result->fees)) {
            throw new NotFoundHttpException();
        }

        return new Response();
    }
}

/* return new Response(
     $serializer->serialize($result->name, "json")
 );*/