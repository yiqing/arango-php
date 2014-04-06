<?php

namespace MikeRoetgers\ArangoPHP\Collection\Option;

use MikeRoetgers\ArangoPHP\Option\OptionCollection;

class CreateCollectionOptions implements OptionCollection
{
    /**
     * @var bool
     */
    private $waitForSync = false;

    /**
     * @var bool
     */
    private $doCompact = true;

    /**
     * @var null|int
     */
    private $journalSize = null;

    /**
     * @var bool
     */
    private $isSystem = false;

    /**
     * @var bool
     */
    private $isVolatile = false;

    /**
     * Valid keys are: type, allowUserKeys, increment, offset
     *
     * @var null|array
     */
    private $keyOptions = null;

    /**
     * @param boolean $doCompact
     */
    public function setDoCompact($doCompact)
    {
        $this->doCompact = $doCompact;
    }

    /**
     * @return boolean
     */
    public function getDoCompact()
    {
        return $this->doCompact;
    }

    /**
     * @param boolean $isSystem
     */
    public function setIsSystem($isSystem)
    {
        $this->isSystem = $isSystem;
    }

    /**
     * @return boolean
     */
    public function getIsSystem()
    {
        return $this->isSystem;
    }

    /**
     * @param boolean $isVolatile
     */
    public function setIsVolatile($isVolatile)
    {
        $this->isVolatile = $isVolatile;
    }

    /**
     * @return boolean
     */
    public function getIsVolatile()
    {
        return $this->isVolatile;
    }

    /**
     * @param int|null $journalSize
     */
    public function setJournalSize($journalSize)
    {
        $this->journalSize = $journalSize;
    }

    /**
     * @return int|null
     */
    public function getJournalSize()
    {
        return $this->journalSize;
    }

    /**
     * @param array|null $keyOptions
     */
    public function setKeyOptions($keyOptions)
    {
        $this->keyOptions = $keyOptions;
    }

    /**
     * @return array|null
     */
    public function getKeyOptions()
    {
        return $this->keyOptions;
    }

    /**
     * @param boolean $waitForSync
     */
    public function setWaitForSync($waitForSync)
    {
        $this->waitForSync = $waitForSync;
    }

    /**
     * @return boolean
     */
    public function getWaitForSync()
    {
        return $this->waitForSync;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array(
            'waitForSync' => $this->waitForSync,
            'doCompact' => $this->doCompact,
            'isSystem' => $this->isSystem,
            'isVolatile' => $this->isVolatile
        );

        if (!empty($this->journalSize)) {
            $data['journalSize'] = $this->journalSize;
        }

        if (!empty($this->keyOptions)) {
            $data['keyOptions'] = $this->keyOptions;
        }

        return $data;
    }
}