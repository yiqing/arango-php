<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

class Cursor
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $hasMore = true;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function hasMore()
    {
        return $this->hasMore;
    }

    /**
     * @param boolean $flag
     */
    public function setMore($flag = true)
    {
        $this->hasMore = $flag;
    }

}