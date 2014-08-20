<?php

namespace MikeRoetgers\ArangoPHP\AQL;

use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Cursor;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class CursorManager
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
     * @param Cursor $cursor
     * @return Response
     */
    public function fetchNextBatch(Cursor $cursor)
    {
        $request = new Request('/_api/cursor/' . $cursor->getId());
        $request->setMethod(Request::METHOD_PUT);

        $handler = new ResponseHandler();
        $handler->onStatusCode(200)->execute(function(Response $response) {
            return $response;
        });
        $handler->onStatusCode(404)->throwUnknownCursorException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();
        return $handler->handle($this->client->sendRequest($request));
    }
}