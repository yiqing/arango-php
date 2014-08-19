<?php

namespace MikeRoetgers\ArangoPHP\AQL;

use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class AQLService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Query $query
     * @return Response
     */
    public function query(Query $query)
    {
        $body = [
            'query' => $query->getQuery(),
            'count' => $query->getCount(),
            'batchSize' => $query->getBatchSize(),
            'bindVars' => $query->getVars(),
            'options' => $query->getOptions()
        ];

        $request = new Request('/_api/cursor', Request::METHOD_POST);
        $request->setBody(json_encode($body));
        return $this->client->sendRequest($request);
    }

}