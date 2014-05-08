<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\DataMapper\EntityAutoMapper;

abstract class AbstractDocumentMapper implements DocumentMapper
{
    /**
     * @var EntityAutoMapper
     */
    protected $autoMapper;

    /**
     * Instantiable class name (either fully qualified or short combined with use statement)
     *
     * @var string
     */
    protected $entity;

    /**
     * List of field names in the document
     *
     * @var array
     */
    protected $fields = array();

    /**
     * @param EntityAutoMapper $autoMapper
     */
    public function __construct(EntityAutoMapper $autoMapper)
    {
        $this->autoMapper = $autoMapper;
    }

    /**
     * @param array $document
     * @return mixed
     */
    public function mapDocument(array $document)
    {
        $entity = new $this->entity();

        foreach ($document as $key => $value) {
            $this->autoMapper->autoSet($key, $value, $entity);
        }

        return $entity;
    }

    /**
     * @param mixed $entity
     * @return array
     */
    public function mapEntity($entity)
    {
        $data = array();

        foreach ($this->fields as $field) {
            $data[$field] = $this->autoMapper->autoGet($field, $entity);
        }

        return $data;
    }

    /**
     * @param array $documents
     * @return mixed
     */
    public function mapDocuments(array $documents = array())
    {
        $result = array();

        foreach ($documents as $document) {
            $this->mapDocument($document);
        }

        return $result;
    }

    /**
     * @param array $entities
     * @return array
     */
    public function mapEntities(array $entities)
    {
        $result = array();

        foreach ($entities as $entity) {
            $this->mapEntity($entity);
        }

        return $result;
    }
}