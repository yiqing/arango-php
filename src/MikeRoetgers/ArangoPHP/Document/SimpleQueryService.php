<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\ArangoPHP\AbstractService;
use MikeRoetgers\ArangoPHP\Document\Options\RemoveByExampleOptions;
use MikeRoetgers\ArangoPHP\Document\Options\SimpleQueryOptions;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class SimpleQueryService extends AbstractService
{
    /**
     * @param string $collectionName
     * @param SimpleQueryOptions $options
     * @return Response
     */
    public function fetchAll($collectionName, SimpleQueryOptions $options = null)
    {
        if ($options === null) {
            $options = new SimpleQueryOptions();
        }

        $body = [
            'collection' => $collectionName
        ];

        if ($options->skip !== null) {
            $body['skip'] = $options->skip;
        }

        if ($options->limit !== null) {
            $body['limit'] = $options->limit;
        }

        $request = new Request('/_api/simple/all', Request::METHOD_PUT);
        $request->setBody(json_encode($body));

        $response = $this->client->sendRequest($request);

        if ($this->shouldHandleErrors()) {
            return $response;
        }

        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->returnResponse();
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($response);
    }

    /**
     * @param string $collectionName
     * @param array $example
     * @param SimpleQueryOptions $options
     * @return Response
     */
    public function fetchByExample($collectionName, array $example, SimpleQueryOptions $options = null)
    {
        if ($options === null) {
            $options = new SimpleQueryOptions();
        }

        $body = [
            'collection' => $collectionName,
            'example' => $example
        ];

        if ($options->skip !== null) {
            $body['skip'] = $options->skip;
        }

        if ($options->limit !== null) {
            $body['limit'] = $options->limit;
        }

        $request = new Request('/_api/simple/by-example', Request::METHOD_PUT);
        $request->setBody(json_encode($body));

        $response = $this->client->sendRequest($request);

        if ($this->shouldHandleErrors()) {
            return $response;
        }

        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->returnResponse();
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($response);
    }

    /**
     * @param string $collectionName
     * @param array $example
     * @return Response
     */
    public function fetchFirstByExample($collectionName, array $example)
    {
        $body = [
            'collection' => $collectionName,
            'example' => $example
        ];

        $request = new Request('/_api/simple/first-example', Request::METHOD_PUT);
        $request->setBody(json_encode($body));

        $response = $this->client->sendRequest($request);

        if ($this->shouldHandleErrors()) {
            return $response;
        }

        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->returnResponse();
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($response);
    }

    /**
     * @param string $collectionName
     * @param array $example
     * @param RemoveByExampleOptions $options
     * @return Response
     */
    public function removeByExample($collectionName, array $example, RemoveByExampleOptions $options = null)
    {
        if ($options === null) {
            $options = new RemoveByExampleOptions();
        }

        $body = [
            'collection' => $collectionName,
            'example' => $example,
            'options' => [
                'waitForSync' => ($options->waitForSync) ? 'true' : 'false'
            ]
        ];

        if ($options->limit !== null) {
            $body['options']['limit'] = $options->limit;
        }

        $request = new Request('/_api/simple/remove-by-example', Request::METHOD_PUT);
        $request->setBody(json_encode($body));

        $response = $this->client->sendRequest($request);

        if ($this->shouldHandleErrors()) {
            return $response;
        }

        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->returnResponse();
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($response);
    }
}