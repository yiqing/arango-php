<?php

namespace MikeRoetgers\ArangoPHP\Import;

use MikeRoetgers\ArangoPHP\Document\DocumentManager;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;
use MikeRoetgers\ArangoPHP\Import\Exception\ImportException;

class ImportManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param Client $client
     * @param DocumentManager $documentManager
     */
    public function __construct(Client $client, DocumentManager $documentManager)
    {
        $this->client = $client;
        $this->documentManager = $documentManager;
    }

    /**
     * @param string $collection
     * @param string|array $data json string or simple array with data
     * @param bool $waitForSync
     * @param bool $complete
     * @param bool $details
     * @return bool
     */
    public function import($collection, $data, $waitForSync = false, $complete = false, $details = false)
    {
        $query = [
            'collection' => $collection,
            'waitForSync' => ($waitForSync) ? 'true' : 'false',
            'complete' => ($complete) ? 'true' : 'false',
            'details' => ($details) ? 'true' : 'false',
            'type' => 'auto'
        ];

        $request = new Request('/_api/import?' . http_build_query($query));
        $request->setMethod(Request::METHOD_POST);
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $request->setBody($data);

        $response = $this->client->sendRequest($request);

        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function(Response $response) {
            return true;
        });
        $handler->onEverythingElse()->execute(function(Response $response) {
            $body = $response->getBodyAsArray();
            if (empty($body['errorMessage'])) {
                throw new UnexpectedStatusCodeException($response);
            }
            throw new ImportException($body['errorMessage']);
        });
        return $handler->handle($response);
    }

    /**
     * @param string $collection
     * @param array $entities
     * @param bool $waitForSync
     * @param bool $complete
     * @param bool $details
     * @return bool
     * @throws \RuntimeException
     */
    public function importEntities($collection, array $entities, $waitForSync = false, $complete = false, $details = false)
    {
        if (!$this->documentManager->hasMapper($collection)) {
            throw new \RuntimeException('importEntities method can only be used with entities that have a registered mapper.');
        }

        $mapper = $this->documentManager->getMapper($collection);
        return $this->import($collection, $mapper->mapEntities($entities), $waitForSync, $complete, $details);
    }
}