<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler\Behaviour;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler\Exception\UndefinedBehaviourException;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler\FallbackBehaviour;
use MikeRoetgers\ArangoPHP\HTTP\ResponseHandler\StatusCodeBehaviour;

class ResponseHandler
{
    /**
     * @var Behaviour[]
     */
    private $behaviours = array();

    /**
     * @var FallbackBehaviour
     */
    private $fallbackBehaviour;

    public function __construct()
    {
        $this->onEverythingElse()->execute(function(){
            throw new UndefinedBehaviourException();
        });
    }

    public function handle(Response $response)
    {
        foreach ($this->behaviours as $behaviour) {
            if ($behaviour->isValid($response)) {
                $callback = $behaviour->getCallback();
                return $callback($response);
            }
        }

        $callback = $this->fallbackBehaviour->getCallback();
        return $callback($response);
    }

    /**
     * @param int $code
     * @return StatusCodeBehaviour
     */
    public function onStatusCode($code)
    {
        $behaviour = new StatusCodeBehaviour();
        $this->behaviours[] = $behaviour;
        return $behaviour->on($code);
    }

    /**
     * @return FallbackBehaviour
     */
    public function onEverythingElse()
    {
        $this->fallbackBehaviour = new FallbackBehaviour();
        return $this->fallbackBehaviour;
    }
}