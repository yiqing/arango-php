<?php

namespace MikeRoetgers\ArangoPHP\Document;

use MikeRoetgers\DataMapper\AbstractMapper;
use MikeRoetgers\DataMapper\ArrayToEntityMapper;
use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\EntityToArrayMapper;
use MikeRoetgers\DataMapper\GenericMapper;

class DocumentMetadataMapper extends GenericMapper
{
    public function __construct(EntityAutoMapper $autoMapper)
    {
        parent::__construct(
            $autoMapper,
            '\\MikeRoetgers\\ArangoPHP\\Document\\DocumentMetadata',
            array(
                'id' => '_id',
                'ref' => '_ref',
                'key' => '_key'
            )
        );
    }

    /**
     * Overwritten method because this mapper can skip nearly all attributes
     *
     * @param array $row
     * @return DocumentMetadata
     */
    public function mapArrayToEntity(array $row)
    {
        $entity = $this->createNewInstance();

        foreach ($row as $key => $value) {
            if ($key[0] != '_') {
                continue;
            }
            $mappedKey = $this->applyReversedMapping($key);
            $this->autoMapper->autoSet($mappedKey, $value, $entity);
        }

        return $entity;
    }
}