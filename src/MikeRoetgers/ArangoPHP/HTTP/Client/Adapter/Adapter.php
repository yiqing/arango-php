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
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request);
}