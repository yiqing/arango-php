<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

class Cursor
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $count;

    /**
     * @param int $id
     * @param int $count
     */
    public function __construct($id, $count)
    {
        $this->id = $id;
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}