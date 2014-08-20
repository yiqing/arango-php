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
     * @var ImportService
     */
    private $importService;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param ImportService $importService
     * @param DocumentManager $documentManager
     */
    public function __construct(ImportService $importService, DocumentManager $documentManager)
    {
        $this->importService = $importService;
        $this->documentManager = $documentManager;
    }


    /**
     * @param string $collectionName
     * @param string $json
     * @param ImportOptions $options
     * @return bool
     */
    public function importDocuments($collectionName, $json, ImportOptions $options = null)
    {
        $response = $this->importService->importDocuments($collectionName, $json, $options);

        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function() {
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
     * @param $collectionName
     * @param $json
     * @param ImportOptions $options
     * @return bool
     */
    public function importHeadersAndValues($collectionName, $json, ImportOptions $options = null)
    {
        $response = $this->importService->importHeadersAndValues($collectionName, $json, $options);

        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function() {
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
     * @param string $collectionName
     * @param array $entities
     * @param ImportOptions $options
     * @return mixed
     * @throws \RuntimeException
     */
    public function importEntities($collectionName, array $entities, ImportOptions $options = null)
    {
        if (!$this->documentManager->hasMapper($collectionName)) {
            throw new \RuntimeException('importEntities method can only be used with entities that have a registered mapper.');
        }

        $mapper = $this->documentManager->getMapper($collectionName);
        return $this->importDocuments($collectionName, json_encode($mapper->mapEntities($entities)), $options);
    }
}