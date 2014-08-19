<?php

namespace MikeRoetgers\ArangoPHP\Collection;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\Collection\Option\CreateCollectionOptions;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;
use MikeRoetgers\DataMapper\GenericMapper;

class CollectionManager
{
    /**
     * @var CollectionService
     */
    private $collectionService;

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
        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(Response $response){
            return $this->collectionMapper->mapArrayToEntity($response->getBodyAsArray());
        });
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->collectionService->createCollection($name, $options));
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
        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(){
            return true;
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->collectionService->deleteCollection($name));
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
        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(){
            return true;
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->collectionService->truncateCollection($name));
    }

    /**
     * @param string $name
     * @return Collection
     * @throws Exception\UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     */
    public function getCollection($name)
    {
        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(Response $response){
            return $this->collectionMapper->mapArrayToEntity($response->getBodyAsArray());
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->collectionService->getCollection($name));
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
        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(Response $response){
            return $response->getBodyAsArray()['count'];
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->collectionService->countDocumentsInCollection($name));
    }
}