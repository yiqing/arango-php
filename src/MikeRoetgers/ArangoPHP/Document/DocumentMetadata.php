<?php

namespace MikeRoetgers\ArangoPHP\Document;

class DocumentMetadata
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $rev;

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $rev
     */
    public function setRef($rev)
    {
        $this->rev = $rev;
    }

    /**
     * @return string
     */
    public function getRef()
    {
        return $this->rev;
    }
}