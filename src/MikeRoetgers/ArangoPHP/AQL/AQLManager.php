<?php

namespace MikeRoetgers\ArangoPHP\AQL;

use MikeRoetgers\ArangoPHP\Document\DocumentManager;
use MikeRoetgers\ArangoPHP\HTTP\Response;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

class AQLManager
{
    /**
     * @var AQLService
     */
    private $aqlService;

    /**
     * @var CursorManager
     */
    private $cursorManager;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param AQLService $aqlService
     * @param CursorManager $cursorManager
     * @param DocumentManager $documentManager
     */
    public function __construct(AQLService $aqlService, CursorManager $cursorManager, DocumentManager $documentManager)
    {
        $this->aqlService = $aqlService;
        $this->cursorManager = $cursorManager;
        $this->documentManager = $documentManager;
    }

    /**
     * @param Query $query
     * @param string $mapperName
     * @return mixed
     */
    public function query(Query $query, $mapperName)
    {
        $handler = new ResponseHandler();
        $handler->onStatusCode(201)->execute(function(Response $response) {
            return $response;
        });
        $handler->onStatusCode(400)->throwInvalidRequestException();
        $handler->onStatusCode(404)->throwUnknownCollectionException();
        $handler->onEverythingElse()->throwUnexpectedStatusCodeException();

        $response = $this->aqlService->query($query);
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

        return $this->documentManager->getMapper($mapperName)->mapDocuments($data);
    }
}