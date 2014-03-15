<?php

namespace MikeRoetgers\ArangoPHP\User;

use MikeRoetgers\DataMapper\AbstractMapper;
use MikeRoetgers\DataMapper\ArrayToEntityMapper;
use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\EntityToArrayMapper;

class UserMapper extends AbstractMapper implements ArrayToEntityMapper, EntityToArrayMapper
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
     * @return User
     */
    public function mapArrayToEntity(array $row, array $mappings = array())
    {
        $user = new User();

        foreach ($row as $key => $value) {
            $key = $this->applyMapping($key, $mappings);
            $this->autoMapper->autoSet($key, $value, $user);
        }

        return $user;
    }

    public function massMapArrayToEntity(array $rows, array $mappings = array())
    {
        // TODO: Implement massMapArrayToEntity() method.
    }

    /**
     * @param $entity
     * @param array $mappings
     * @return array
     */
    public function mapEntityToArray($entity, array $mappings = array())
    {
        $keys = array('username', 'passwd', 'active', 'extra');
        $data = array();

        foreach ($keys as $key) {
            $key = $this->applyMapping($key, $mappings);
            $data[$key] = $this->autoMapper->autoGet($key, $entity);
        }

        return $data;
    }

    public function massMapEntityToArray(array $entities, array $mappings = array())
    {
        // TODO: Implement massMapEntityToArray() method.
    }
}