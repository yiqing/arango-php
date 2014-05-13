<?php

namespace MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

use MikeRoetgers\ArangoPHP\Collection\Exception\UnknownCollectionException;
use MikeRoetgers\ArangoPHP\Database\Exception\UnknownDatabaseException;
use MikeRoetgers\ArangoPHP\Document\Exception\UnknownDocumentException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\ConflictException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Response;

abstract class Behaviour
{
    protected $callback;

    public function execute($callback)
    {
        $this->callback = $callback;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function throwUnknownCollectionException()
    {
        $this->callback = function() {
            throw new UnknownCollectionException();
        };
    }

    public function throwUnknownDocumentException()
    {
        $this->callback = function() {
            throw new UnknownDocumentException();
        };
    }

    public function throwUnknownDatabaseException()
    {
        $this->callback = function() {
            throw new UnknownDatabaseException();
        };
    }

    public function throwConflictException()
    {
        $this->callback = function() {
            throw new ConflictException();
        };
    }

    public function throwInvalidRequestException()
    {
        $this->callback = function() {
            throw new InvalidRequestException();
        };
    }

    public function throwUnexpectedStatusCodeException()
    {
        $this->callback = function(Response $response) {
            throw new UnexpectedStatusCodeException($response);
        };
    }

    abstract public function isValid(Response $response);
}