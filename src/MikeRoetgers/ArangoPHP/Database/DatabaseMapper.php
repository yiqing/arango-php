<?php

namespace MikeRoetgers\ArangoPHP\Database;

use MikeRoetgers\DataMapper\ArrayToEntityMapper;
use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\EntityToJsonMapper;
use MikeRoetgers\DataMapper\JsonToEntityMapper;

class DatabaseMapper
{
    /**
     * @var EntityAutoMapper
     */
    private $autoMapper;

    public function mapEntityToArray($entity, array $mappings = array())
    {
        $keys = array('name', 'id', 'path', 'isSystem');
        $data = array();

        foreach ($keys as $key) {
            $key = $this->applyMapping($key, $mappings);
            $data[$key] = $this->autoMapper->autoGet($key, $entity);
        }

        return $data;
    }

    /**
     * @param array $row
     * @param array $mappings
     * @return Database
     */
    public function mapArrayToEntity(array $row, array $mappings = array())
    {
        $database = new Database();

        foreach ($row as $key => $value) {
            $key = $this->applyMapping($key, $mappings);
            $this->autoMapper->autoSet($key, $value, $database);
        }

        return $database;
    }

    public function applyMapping($key, array $mapping)
    {
        if (isset($mapping[$key])) {
            return $mapping[$key];
        }
        return $key;
    }
}