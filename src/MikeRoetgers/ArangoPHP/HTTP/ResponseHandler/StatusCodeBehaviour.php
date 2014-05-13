<?php

namespace MikeRoetgers\ArangoPHP\HTTP\ResponseHandler;

use MikeRoetgers\ArangoPHP\HTTP\Response;

class StatusCodeBehaviour extends Behaviour
{
    /**
     * @var int
     */
    private $code;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function on($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param Response $response
     * @return bool
     */
    public function isValid(Response $response)
    {
        return $this->code == $response->getStatusCode();
    }
}