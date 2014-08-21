<?php

namespace MikeRoetgers\ArangoPHP\Document\Options;

class DeleteDocumentOptions
{
    public $waitForSync = false;
    public $rev = null;
    public $policy = null;
    public $ifMatch = null;
}