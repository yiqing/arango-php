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
     * @var bool
     */
    private $hasMore = true;

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