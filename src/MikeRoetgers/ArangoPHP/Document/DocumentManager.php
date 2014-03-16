<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\Document\Exception\UnknownDocumentException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;

class DocumentManager
{
    /**
     * @var DocumentMapper[]
     */
    private $mappers = array();

    /**
     * @var DocumentMetadataMapper
     */
    private $metadataMapper;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param DocumentMetadataMapper $metadataMapper
     */
    public function __construct(Client $client, DocumentMetadataMapper $metadataMapper)
    {
        $this->client = $client;
        $this->metadataMapper = $metadataMapper;
    }

    /**
     * @param string $collectionName
     * @param DocumentMapper $mapper
     */
    public function registerMapper($collectionName, DocumentMapper $mapper)
    {
        $this->mappers[$collectionName] = $mapper;
    }

    /**
     * @param string $documentHandle
     * @param string $etag if provided and etag does not match, returns false
     * @return bool|mixed
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     * @throws Exception\UnknownDocumentException
     */
    public function getDocument($documentHandle, $etag = null)
    {
        $request = new Request('/_api/document/' . $documentHandle);

        if ($etag !== null) {
            $request->addHeader('If-Match', $etag);
        }

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                $collectionName = explode('/', $documentHandle)[0];
                if ($this->hasMapper($collectionName)) {
                    $result = $response->getBodyAsArray()['result'];
                    $entity = $this->getMapper($collectionName)->mapDocument($result);
                    if ($entity instanceof MetadataAware) {
                        $metadata = $this->metadataMapper->mapArrayToEntity($result);
                        $entity->setMetadata($metadata);
                    }
                    return $entity;
                }
                return $response->getBodyAsArray()['result'];
                break;
            case 400:
                throw new InvalidRequestException();
                break;
            case 404:
                throw new UnknownDocumentException();
                break;
            case 412:
                return false;
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $collectionName
     * @param mixed $entity
     * @param bool $createCollection
     * @param bool $waitForSync
     * @return bool
     * @throws InvalidRequestException
     * @throws UnknownCollectionException
     * @throws UnexpectedStatusCodeException
     */
    public function createDocument($collectionName, $entity, $createCollection = false, $waitForSync = false)
    {
        if ($this->hasMapper($collectionName)) {
            $entity = $this->getMapper($collectionName)->mapEntity($entity);
        }

        $query = array(
            'collectionName' => $collectionName
        );

        if ($createCollection) {
            $query['createCollection'] = 'true';
        } else {
            $query['createCollection'] = 'false';
        }

        if ($waitForSync) {
            $query['waitForSync'] = 'true';
        } else {
            $query['waitForSync'] = 'false';
        }

        $request = new Request('/_api/document?' . http_build_query($query));
        $request->setMethod(Request::METHOD_POST);
        $request->setBody(json_encode($entity));

        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 201:
            case 202:
                if ($entity instanceof MetadataAware) {

                }
                return true;
            case 400:
                throw new InvalidRequestException($response->getBodyAsArray()['errorMessage']);
                break;
            case 404:
                throw new UnknownCollectionException();
                break;
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    public function replaceDocument()
    {

    }

    /**
     * @param $collectionName
     * @return bool
     */
    private function hasMapper($collectionName)
    {
        return isset($this->mappers[$collectionName]);
    }

    /**
     * @param $collectionName
     * @return DocumentMapper
     */
    private function getMapper($collectionName)
    {
        return $this->mappers[$collectionName];
    }
}