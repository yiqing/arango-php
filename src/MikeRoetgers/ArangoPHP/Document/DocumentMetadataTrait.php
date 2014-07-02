<?php

namespace MikeRoetgers\ArangoPHP\Document;

trait DocumentMetadataTrait
{
    /**
     * @var DocumentMetadata
     */
    protected $documentMetadata;

    /**
     * @param DocumentMetadata $metadata
     */
    public function setMetadata(DocumentMetadata $metadata)
    {
        $this->documentMetadata = $metadata;
    }

    /**
     * @return DocumentMetadata
     */
    public function getMetadata()
    {
        return $this->documentMetadata;
    }
}