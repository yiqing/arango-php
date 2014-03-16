<?php

namespace MikeRoetgers\ArangoPHP\Document;

interface DocumentMapper
{
    /**
     * @param array $document
     * @return mixed
     */
    public function mapDocument(array $document);

    /**
     * @param array $documents
     * @return mixed
     */
    public function mapDocuments(array $documents = array());

    /**
     * @param mixed $entity
     * @return array
     */
    public function mapEntity($entity);

    /**
     * @param array $entities
     * @return array
     */
    public function mapEntities(array $entities);
}