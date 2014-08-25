<?php

namespace MikeRoetgers\ArangoPHP;

use MikeRoetgers\ArangoPHP\HTTP\Client\Client;

abstract class AbstractService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $errorHandling = true;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function activateErrorHandling()
    {
        $this->errorHandling = true;
    }

    public function deactivateErrorHandling()
    {
        $this->errorHandling = false;
    }

    /**
     * @return bool
     */
    protected function shouldHandleErrors()
    {
        return $this->errorHandling;
    }
}