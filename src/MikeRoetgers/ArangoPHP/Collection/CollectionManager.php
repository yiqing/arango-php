<?php

namespace MikeRoetgers\ArangoPHP\Collection;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\Collection\Option\CreateCollectionOptions;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\DataMapper\GenericMapper;

class CollectionManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var GenericMapper
     */
    private $collectionMapper;

    /**
     * @param string $name
     * @param CreateCollectionOptions $options
     * @return Collection
     * @throws UnexpectedStatusCodeException
     */
    public function createCollection($name, CreateCollectionOptions $options = null)
    {
        if ($options === null) {
            $options = new CreateCollectionOptions();
        }

        $request = new Request('/_api/collection', Request::METHOD_POST);
        $request->setBody(json_encode(array_merge(array('name' => $name), $options->toArray())));

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->collectionMapper->mapArrayToEntity($response->getBodyAsArray());
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @return bool
     * @throws InvalidRequestException
     * @throws Exception\UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     */
    public function deleteCollection($name)
    {
        $request = new Request('/_api/collection/' . $name, Request::METHOD_DELETE);

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return true;
            case 400:
                throw new InvalidRequestException();
            case 404:
                throw new UnknownCollectionException();
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @return bool
     * @throws InvalidRequestException
     * @throws Exception\UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     */
    public function truncateCollection($name)
    {
        $request = new Request('/_api/collection/' . $name . '/truncate', Request::METHOD_PUT);

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return true;
            case 400:
                throw new InvalidRequestException();
            case 404:
                throw new UnknownCollectionException();
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @return Collection
     * @throws Exception\UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     */
    public function getCollection($name)
    {
        $request = new Request('/_api/collection/' . $name);

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->collectionMapper->mapArrayToEntity($response->getBodyAsArray());
            case 404:
                throw new UnknownCollectionException();
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @throws InvalidRequestException
     * @throws Exception\UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     * @return int
     */
    public function countDocumentsInCollection($name)
    {
        $request = new Request('/_api/collection/' . $name . '/count');

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->collectionMapper->mapArrayToEntity($response->getBodyAsArray()['count']);
            case 400:
                throw new InvalidRequestException();
            case 404:
                throw new UnknownCollectionException();
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }
}