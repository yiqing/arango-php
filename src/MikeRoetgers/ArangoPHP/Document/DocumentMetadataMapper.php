<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\DataMapper\AbstractMapper;
use MikeRoetgers\DataMapper\ArrayToEntityMapper;
use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\EntityToArrayMapper;

class DocumentMetadataMapper extends AbstractMapper implements ArrayToEntityMapper
{
    /**
     * @var EntityAutoMapper
     */
    private $autoMapper;

    /**
     * @param EntityAutoMapper $autoMapper
     */
    public function __construct(EntityAutoMapper $autoMapper)
    {
        $this->autoMapper = $autoMapper;
    }

    /**
     * @param array $row
     * @param array $mappings
     * @return DocumentMetadata
     */
    public function mapArrayToEntity(array $row, array $mappings = array())
    {
        $metadata = new DocumentMetadata();

        foreach ($row as $key => $value) {
            $key = $this->applyMapping($key, $mappings);
            $this->autoMapper->autoSet($key, $value, $metadata);
        }

        return $metadata;
    }

    /**
     * @param array $rows
     * @param array $mappings
     * @return DocumentMetadata[]
     */
    public function massMapArrayToEntity(array $rows, array $mappings = array())
    {
        $result = array();

        foreach ($rows as $row) {
            $result[] = $this->mapArrayToEntity($row, $mappings);
        }

        return $result;
    }
}