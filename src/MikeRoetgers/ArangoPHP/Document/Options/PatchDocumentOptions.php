<?php

namespace MikeRoetgers\ArangoPHP\Document\Options;

class PatchDocumentOptions
{
    public $keepNull = true;
    public $waitForSync = false;
    public $rev = null;
    public $policy = null;
    public $ifMatch = null;
}