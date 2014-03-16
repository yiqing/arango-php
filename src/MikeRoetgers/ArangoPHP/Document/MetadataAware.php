<?php

namespace MikeRoetgers\ArangoPHP\Document;

interface MetadataAware
{
    /**
     * @param DocumentMetadata $metadata
     */
    public function setMetadata(DocumentMetadata $metadata);

    /**
     * @return DocumentMetadata
     */
    public function getMetadata();
}