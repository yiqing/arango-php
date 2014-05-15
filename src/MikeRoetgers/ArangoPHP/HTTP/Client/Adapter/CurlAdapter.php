<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client\Adapter;

use MikeRoetgers\ArangoPHP\HTTP\Request;

class CurlAdapter implements Adapter
{
    /**
     * @var string
     */
    private $databaseUrl;

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $url = $this->databaseUrl . $request->getPath();
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        if ($request->getMethod() == Request::METHOD_POST || $request->getMethod() == Request::METHOD_PUT) {
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request->getBody());
        }

        $body = curl_exec($curlHandle);

        $response = new Response();
        $response->setBody($body);
        $response->setStatusCode(curl_getinfo($curlHandle, CURLINFO_HTTP_CODE));
        $response->setContentType(curl_getinfo($curlHandle, CURLINFO_CONTENT_TYPE));

        curl_close($curlHandle);

        return $response;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setDatabaseUrl($url)
    {
        $this->databaseUrl = $url;
    }
}