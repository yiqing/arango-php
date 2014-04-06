<?php

namespace MikeRoetgers\ArangoPHP\Collection;

use MikeRoetgers\DataMapper\AbstractMapper;
use MikeRoetgers\DataMapper\ArrayToEntityMapper;
use MikeRoetgers\DataMapper\EntityAutoMapper;

class CollectionMapper extends AbstractMapper implements ArrayToEntityMapper
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
     * @return Collection
     */
    public function mapArrayToEntity(array $row, array $mappings = array())
    {
        $collection = new Collection();

        foreach ($row as $key => $value) {
            $key = $this->applyMapping($key, $mappings);
            $this->autoMapper->autoSet($key, $value, $collection);
        }

        return $collection;
    }

    /**
     * @param array $rows
     * @param array $mappings
     * @return array
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