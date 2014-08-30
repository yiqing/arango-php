<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\Document\Exception\UnknownDocumentException;
use MikeRoetgers\ArangoPHP\Document\Options\CreateDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\DeleteDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\GetDocumentOptions;
use MikeRoetgers\ArangoPHP\Document\Options\ReplaceDocumentOptions;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

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
     * @var DocumentService
     */
    private $documentService;

    /**
     * @param DocumentService $documentService
     * @param DocumentMetadataMapper $metadataMapper
     */
    public function __construct(DocumentService $documentService, DocumentMetadataMapper $metadataMapper)
    {
        $this->documentService = $documentService;
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
     * @param Options\GetDocumentOptions $options
     * @return array
     */
    public function getDocument($documentHandle, GetDocumentOptions $options = null)
    {
        $response = $this->documentService->getDocument($documentHandle, $options);
        $collectionName = explode('/', $documentHandle)[0];
        $mapper = $this->getMapper($collectionName);
        return $mapper->mapDocuments($response->getBodyAsArray()['result']);
    }

    /**
     * @param string $collectionName
     * @param MetadataAware $entity
     * @param CreateDocumentOptions $options
     * @return MetadataAware
     */
    public function createDocument($collectionName, MetadataAware $entity, CreateDocumentOptions $options = null)
    {
        $document = $this->getMapper($collectionName)->mapEntity($entity);
        $response = $this->documentService->createDocument($collectionName, $document, $options);
        $entity->setMetadata($this->metadataMapper->mapArrayToEntity($response->getBodyAsArray()));
        return $entity;
    }

    /**
     * @param MetadataAware $entity
     * @param Options\ReplaceDocumentOptions $options
     */
    public function replaceDocument(MetadataAware $entity, ReplaceDocumentOptions $options = null)
    {
        $collectionName = explode('/', $entity->getMetadata()->getId())[0];
        $response = $this->documentService->replaceDocument(
            $entity->getMetadata()->getId(),
            $this->getMapper($collectionName)->mapEntity($entity),
            $options
        );
        $entity->setMetadata($this->metadataMapper->mapArrayToEntity($response->getBodyAsArray()));
    }

    public function deleteDocument(MetadataAware $entity, DeleteDocumentOptions $options = null)
    {
        $this->documentService->deleteDocument($entity->getMetadata()->getId(), $options);
    }

    /**
     * @param $collectionName
     * @return bool
     */
    public function hasMapper($collectionName)
    {
        return isset($this->mappers[$collectionName]);
    }

    /**
     * @param $collectionName
     * @throws \RuntimeException
     * @return DocumentMapper
     */
    public function getMapper($collectionName)
    {
        if (!$this->hasMapper($collectionName)) {
            throw new \RuntimeException('Mapper with name "' . $collectionName . '" is unknown.');
        }
        return $this->mappers[$collectionName];
    }

    /**
     * @return DocumentService
     */
    public function getDocumentService()
    {
        return $this->documentService;
    }
}