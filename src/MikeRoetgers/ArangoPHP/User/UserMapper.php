<?php

namespace MikeRoetgers\ArangoPHP\User;

use MikeRoetgers\DataMapper\EntityAutoMapper;
use MikeRoetgers\DataMapper\GenericMapper;

class UserMapper extends GenericMapper
{
    public function __construct(EntityAutoMapper $autoMapper)
    {
        parent::__construct($autoMapper, '\\MikeRoetgers\\ArangoPHP\\User\\User');
    }

}