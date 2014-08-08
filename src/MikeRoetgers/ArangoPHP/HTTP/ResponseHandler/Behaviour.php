<?php

namespace MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

use MikeRoetgers\ArangoPHP\AQL\Exception\UnknownCursorException;
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
        $this->callback = function(Response $response) {
            throw new UnknownCollectionException($response);
        };
    }

    public function throwUnknownDocumentException()
    {
        $this->callback = function(Response $response) {
            throw new UnknownDocumentException($response);
        };
    }

    public function throwUnknownDatabaseException()
    {
        $this->callback = function(Response $response) {
            throw new UnknownDatabaseException($response);
        };
    }

    public function throwUnknownCursorException()
    {
        $this->callback = function(Response $response) {
            throw new UnknownCursorException($response);
        };
    }

    public function throwConflictException()
    {
        $this->callback = function(Response $response) {
            throw new ConflictException($response);
        };
    }

    public function throwInvalidRequestException()
    {
        $this->callback = function(Response $response) {
            throw new InvalidRequestException($response);
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