<?php

namespace MikeRoetgers\ArangoPHP\Collection;

use MikeRoetgers\ArangoPHP\Collection\Option\CreateCollectionOptions;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class CollectionService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $name
     * @param CreateCollectionOptions $options
     * @return Response
     */
    public function createCollection($name, CreateCollectionOptions $options = null)
    {
        if ($options === null) {
            $options = new CreateCollectionOptions();
        }

        $request = new Request('/_api/collection', Request::METHOD_POST);
        $request->setBody(json_encode(array_merge(array('name' => $name), $options->toArray())));

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $name
     * @return Response
     */
    public function deleteCollection($name)
    {
        $request = new Request('/_api/collection/' . $name, Request::METHOD_DELETE);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $name
     * @return Response
     */
    public function truncateCollection($name)
    {
        $request = new Request('/_api/collection/' . $name . '/truncate', Request::METHOD_PUT);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $name
     * @return Response
     */
    public function getCollection($name)
    {
        $request = new Request('/_api/collection/' . $name);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $name
     * @return Response
     */
    public function countDocumentsInCollection($name)
    {
        $request = new Request('/_api/collection/' . $name . '/count');

        return $this->client->sendRequest($request);
    }
}