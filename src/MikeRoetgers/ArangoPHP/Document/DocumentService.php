<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\ArangoPHP\Document\Options\CreateDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\DeleteDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\GetDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\PatchDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\ReplaceDocumentOptions;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class DocumentService
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
     * @param string $documentHandle
     * @param GetDocumentOptions $options
     * @return Response
     */
    public function getDocument($documentHandle, GetDocumentOptions $options = null)
    {
        if ($options === null) {
            $options = new GetDocumentOptions();
        }

        $request = new Request('/_api/document/' . $documentHandle);

        if ($options->ifMatch !== null) {
            $request->addHeader('If-Match', $options->ifMatch);
        }

        if ($options->ifNoneMatch !== null) {
            $request->addHeader('If-None-Match', $options->ifNoneMatch);
        }

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $collectionName
     * @param string $document
     * @param CreateDocumentOptions $options
     * @return Response
     */
    public function createDocument($collectionName, $document, CreateDocumentOptions $options = null)
    {
        if ($options === null) {
            $options = new CreateDocumentOptions();
        }

        $query = [
            'collection' => $collectionName,
            'createCollection' => ($options->createCollection) ? 'true' : 'false',
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false'
        ];

        $request = new Request('/_api/document?' . http_build_query($query));
        $request->setMethod(Request::METHOD_POST);
        $request->setBody($document);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $documentHandle
     * @param string $document
     * @param ReplaceDocumentOptions $options
     * @return Response
     */
    public function replaceDocument($documentHandle, $document, ReplaceDocumentOptions $options = null)
    {
        if ($options === null) {
            $options = new ReplaceDocumentOptions();
        }

        $query = [
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false'
        ];

        if ($options->rev !== null) {
            $query['rev'] = $options->rev;
        }

        if ($options->policy !== null) {
            $query['policy'] = $options->policy;
        }

        $request = new Request('/_api/document/' . $documentHandle . '?' . http_build_query($query));
        $request->setMethod(Request::METHOD_PUT);

        if ($options->ifMatch !== null) {
            $request->addHeader('If-Match', $options->ifMatch);
        }

        $request->setBody($document);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $documentHandle
     * @param string $document
     * @param PatchDocumentOptions $options
     * @return Response
     */
    public function patchDocument($documentHandle, $document, PatchDocumentOptions $options = null)
    {
        if ($options === null) {
            $options = new PatchDocumentOptions();
        }

        $query = [
            'keepNull' => ($options->keepNull) ? 'true' : 'false',
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false'
        ];

        if ($options->rev !== null) {
            $query['rev'] = $options->rev;
        }

        if ($options->policy !== null) {
            $query['policy'] = $options->policy;
        }

        $request = new Request('/_api/document/' . $documentHandle . '?' . http_build_query($query));
        $request->setMethod(Request::METHOD_PATCH);
        $request->setBody($document);

        if ($options->ifMatch !== null) {
            $request->addHeader('If-Match', $options->ifMatch);
        }

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $documentHandle
     * @param DeleteDocumentOptions $options
     * @return Response
     */
    public function deleteDocument($documentHandle, DeleteDocumentOptions $options = null)
    {
        if ($options === null) {
            $options = new DeleteDocumentOptions();
        }

        $query = [
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false'
        ];

        if ($options->rev !== null) {
            $query['rev'] = $options->rev;
        }

        if ($options->policy !== null) {
            $query['policy'] = $options->policy;
        }

        $request = new Request('/_api/document/' . $documentHandle . '?' . http_build_query($query));
        $request->setMethod(Request::METHOD_DELETE);

        if ($options->ifMatch !== null) {
            $request->addHeader('If-Match', $options->ifMatch);
        }

        return $this->client->sendRequest($request);
    }
}