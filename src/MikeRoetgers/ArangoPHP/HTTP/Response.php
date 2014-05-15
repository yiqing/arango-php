<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

class Response
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var Cursor
     */
    private $cursor;

    /**
     * @return array
     */
    public function getBodyAsArray()
    {
        return json_decode($this->body, true);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param Cursor $cursor
     */
    public function setCursor($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @return Cursor
     */
    public function getCursor()
    {
        return $this->cursor;
    }
}