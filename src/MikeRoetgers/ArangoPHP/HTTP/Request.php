<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $body;

    /**
     * @param string $path
     * @param string $method
     */
    public function __construct($path, $method = self::METHOD_GET)
    {
        $this->path = $path;
        $this->setMethod($method);
    }

    /**
     * @param string $method
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setMethod($method)
    {
        if (!in_array($method, array(self::METHOD_GET, self::METHOD_POST, self::METHOD_DELETE))) {
            throw new \InvalidArgumentException('The provided method "' . $method . '" is invalid.');
        }
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}