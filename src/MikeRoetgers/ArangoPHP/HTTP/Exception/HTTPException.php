<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Exception;

use Exception;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class HTTPException extends \RuntimeException
{
    /**
     * @param Response $response
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(Response $response, $code = 0, Exception $previous = null)
    {
        parent::__construct((string)$response, $code, $previous);
    }
}