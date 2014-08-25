<?php

namespace MikeRoetgers\ArangoPHP\HTTP;

use webignition\JsonPrettyPrinter\JsonPrettyPrinter;

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

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 600;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = 'Status Code: ' . $this->statusCode . ' | Content Type: ' . $this->contentType;
        if (!empty($this->cursor)) {
            $str .= ' | Cursor: ' . $this->cursor->getId();
        }
        $str .= ' | Body: ' . "\n";
        $prettyPrinter = new JsonPrettyPrinter();
        $str .= $prettyPrinter->format($this->body);
        return $str;
    }
}