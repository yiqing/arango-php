<?php

namespace MikeRoetgers\ArangoPHP\Document\SingleValue;

use MikeRoetgers\ArangoPHP\Document\DocumentMapper;

class SingleValueDocumentMapper implements DocumentMapper
{

    /**
     * @param array $document
     * @return mixed
     */
    public function mapDocument(array $document)
    {
        $object = new SingleValueDocument();
        $object->setValue($document['value']);
        return $object;
    }

    /**
     * @param array $documents
     * @return mixed
     */
    public function mapDocuments(array $documents = array())
    {
        $objects = array();
        foreach ($documents as $document) {
            $objects[] = $this->mapDocument($document);
        }
        return $objects;
    }

    /**
     * @param SingleValueDocument $entity
     * @return array
     */
    public function mapEntity($entity)
    {
        return array('value' => $entity->getValue());
    }

    /**
     * @param array $entities
     * @return array
     */
    public function mapEntities(array $entities)
    {
        $data = array();
        foreach ($entities as $entity) {
            $data[] = $this->mapEntity($entity);
        }
        return $data;
    }
}