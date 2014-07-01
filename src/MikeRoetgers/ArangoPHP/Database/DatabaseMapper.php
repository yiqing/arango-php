<?php

namespace MikeRoetgers\ArangoPHP\Database;

use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\GenericMapper;

class DatabaseMapper extends GenericMapper
{
    public function __construct(EntityAutoMapper $autoMapper)
    {
        parent::__construct($autoMapper, '\\MikeRoetgers\\ArangoPHP\\Database\\Database');
    }
}