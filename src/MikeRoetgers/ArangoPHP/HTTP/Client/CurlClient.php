<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client;

use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class CurlClient implements Client
{
    /**
     * @var string
     */
    private $databaseUrl;

    public function __construct($databaseUrl = 'http://localhost:8529')
    {
        $this->databaseUrl = $databaseUrl;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $url = $this->databaseUrl . $request->getPath();
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($curlHandle);

        $response = new Response();
        $response->setBody($body);
        $response->setStatusCode(curl_getinfo($curlHandle, CURLINFO_HTTP_CODE));
        $response->setContentType(curl_getinfo($curlHandle, CURLINFO_CONTENT_TYPE));

        curl_close($curlHandle);

        return $response;
    }
}