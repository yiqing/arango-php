<?php

namespace MikeRoetgers\ArangoPHP\AQL;

class Query
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var array
     */
    private $vars = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var int
     */
    private $batchSize = 1000;

    /**
     * @var bool
     */
    private $count = false;

    /**
     * @param string $query
     * @param array $bindVars
     */
    public function __construct($query, array $bindVars = [])
    {
        $this->query = $query;
        $this->vars = $bindVars;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function bindVar($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * @param array $vars
     */
    public function bindVars(array $vars)
    {
        foreach ($vars as $name => $value)
        {
            $this->bindVar($name, $value);
        }
    }

    /**
     * @param int $batchSize
     */
    public function setBatchSize($batchSize)
    {
        $this->batchSize = $batchSize;
    }

    /**
     * @param boolean $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return boolean
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }
}