<?php

namespace MikeRoetgers\ArangoPHP\AQL;

use MikeRoetgers\ArangoPHP\Document\DocumentManager;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class AQLManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var CursorManager
     */
    private $cursorManager;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param Client $client
     * @param CursorManager $cursorManager
     * @param DocumentManager $documentManager
     */
    function __construct(Client $client, CursorManager $cursorManager, DocumentManager $documentManager)
    {
        $this->client = $client;
        $this->cursorManager = $cursorManager;
        $this->documentManager = $documentManager;
    }

    /**
     * @param Query $query
     * @param null $useMapperForCollection
     * @return array|mixed
     */
    public function query(Query $query, $useMapperForCollection = null)
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
        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function(Response $response) {
            return $response;
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();

        /** @var Response $response */
        $response = $handler->handle($this->client->sendRequest($request));
        $data = $response->getBodyAsArray()['result'];
        $cursor = $response->getCursor();
        if (!empty($cursor)) {
            do {
                $response = $this->cursorManager->fetchNextBatch($cursor);
                $data = array_merge($data, $response->getBodyAsArray()['result']);
                $cursor = $response->getCursor();
                if (empty($cursor)) {
                    $cursor = false;
                }
            } while ($cursor !== false);
        }

        if ($useMapperForCollection !== null) {
            return $this->documentManager->getMapper($useMapperForCollection)->mapDocuments($data);
        }
        return $data;
    }
}