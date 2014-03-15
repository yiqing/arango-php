<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client\Exception;

use Exception;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class UnexpectedStatusCodeException extends \Exception
{
    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct('Unexpected status code "' . $response->getStatusCode() . '"', $response->getStatusCode());
    }
}