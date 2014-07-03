<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client\Adapter;

use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class CurlAdapter implements Adapter
{
    /**
     * @var string
     */
    private $databaseUrl;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $url = $this->databaseUrl . $request->getPath();
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

        if (!empty($this->username)) {
            curl_setopt(
                $curlHandle,
                'CURLOPT_HTTPHEADER',
                array('Authorization: Basic ' . base64_encode($this->username . ':' . $this->password))
            );
        }

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

    /**
     * @param string $username
     * @param string $password
     * @return void
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}