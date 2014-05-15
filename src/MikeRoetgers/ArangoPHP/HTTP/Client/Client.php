<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client;

use MikeRoetgers\ArangoPHP\HTTP\Client\Adapter\Adapter;
use MikeRoetgers\ArangoPHP\HTTP\Cursor;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class Client
{
    /**
     * @var string
     */
    private $databaseUrl;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @param string $databaseUrl
     * @param Adapter $adapter
     */
    public function __construct($databaseUrl = 'http://localhost:8529', Adapter $adapter)
    {
        $this->databaseUrl = $databaseUrl;
        $this->adapter = $adapter;
        $adapter->setDatabaseUrl($databaseUrl);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $response = $this->adapter->sendRequest($request);
        if (!empty($response->getBodyAsArray()['hasMore']) && $response->getBodyAsArray()['hasMore'] === true) {
            $body = $response->getBodyAsArray();
            $cursor = new Cursor($body['id'], $body['count']);
            $response->setCursor($cursor);
        }
        return $response;
    }
}