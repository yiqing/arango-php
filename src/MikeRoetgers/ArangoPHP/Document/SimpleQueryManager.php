<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class SimpleQueryManager
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param DocumentManager $documentManager
     */
    public function __construct(Client $client, DocumentManager $documentManager)
    {
        $this->client = $client;
        $this->documentManager = $documentManager;
    }

    public function findAll($collectionName, $skip = 0, $limit = 1000)
    {
        $request = new Request('/_api/simple/all', Request::METHOD_PUT);

        $body = array(
            'collection' => $collectionName,
            'skip' => $skip,
            'limit' => $limit
        );
        $request->setBody(json_encode($body));

        $handler = $this->getResponseHandler($collectionName);
        return $handler->handle($this->client->sendRequest($request));
    }

    public function findByExample($collectionName, array $example, $skip = 0, $limit = 1000)
    {
        $request = new Request('/_api/simple/by-example');
        $request->setMethod(Request::METHOD_PUT);

        $body = array(
            'collection' => $collectionName,
            'example' => $example,
            'skip' => $skip,
            'limit' => $limit
        );
        $request->setBody(json_encode($body));

        return $this->getResponseHandler($collectionName)->handle($this->client->sendRequest($request));
    }

    /**
     * @param string $collectionName
     * @param array $example
     * @return mixed
     */
    public function findFirstByExample($collectionName, array $example)
    {
        $request = new Request('/_api/simple/first-example');
        $request->setMethod(Request::METHOD_PUT);

        $body = array(
            'collection' => $collectionName,
            'example' => $example
        );
        $request->setBody(json_encode($body));

        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(Response $response) use ($collectionName) {
            if ($this->documentManager->hasMapper($collectionName)) {
                $mapper = $this->documentManager->getMapper($collectionName);
                return $mapper->mapDocument($response->getBodyAsArray()['document']);
            }
            return $response->getBodyAsArray()['document'];
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->execute(function(Response $response){
            return null;
        });
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->client->sendRequest($request));
    }

    /**
     * @param string $collectionName
     * @param array $example
     * @return mixed
     */
    public function removeByExample($collectionName, array $example, $waitForSync = false)
    {
        $request = new Request('/_api/simple/remove-by-example');
        $request->setMethod(Request::METHOD_PUT);

        $body = array(
            'collection' => $collectionName,
            'example' => $example
        );
        $request->setBody(json_encode($body));

        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(){
            return true;
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->client->sendRequest($request));
    }

    /**
     * @param $collectionName
     * @return ResponseHandler
     */
    private function getResponseHandler($collectionName)
    {
        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function(Response $response) use ($collectionName) {
            if ($this->documentManager->hasMapper($collectionName)) {
                $mapper = $this->documentManager->getMapper($collectionName);
                return $mapper->mapDocuments($response->getBodyAsArray()['result']);
            }
            return $response->getBodyAsArray()['result'];
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler;
    }
}