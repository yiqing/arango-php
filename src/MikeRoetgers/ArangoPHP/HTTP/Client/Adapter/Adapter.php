<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client\Adapter;

use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

interface Adapter
{
    /**
     * @param string $url
     * @return void
     */
    public function setDatabaseUrl($url);

    /**
     * @param string $username
     * @param string $password
     * @return void
     */
    public function setCredentials($username, $password);

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request);
}