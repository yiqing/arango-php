<?php

namespace MikeRoetgers\ArangoPHP\HTTP\Client;

use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

interface Client
{
    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request);
}