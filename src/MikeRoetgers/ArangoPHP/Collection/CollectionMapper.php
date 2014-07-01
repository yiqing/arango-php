<?php

namespace MikeRoetgers\ArangoPHP\Collection;

use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\GenericMapper;

class CollectionMapper extends GenericMapper
{
    public function __construct(EntityAutoMapper $autoMapper)
    {
        parent::__construct($autoMapper, '\\MikeRoetgers\\ArangoPHP\\Collection\\Collection');
    }

}