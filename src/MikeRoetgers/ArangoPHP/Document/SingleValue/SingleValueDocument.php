<?php

namespace MikeRoetgers\ArangoPHP\Document\SingleValue;

use MikeRoetgers\ArangoPHP\Document\DocumentMetadata;
use MikeRoetgers\ArangoPHP\Document\MetadataAware;

class SingleValueDocument implements MetadataAware
{
    /**
     * @var DocumentMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $value;

    /**
     * @param DocumentMetadata $metadata
     */
    public function setMetadata(DocumentMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return DocumentMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}