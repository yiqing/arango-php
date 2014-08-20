<?php

namespace MikeRoetgers\ArangoPHP\Import;

use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\HTTP\Response;

class ImportService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $collectionName
     * @param string $json
     * @param ImportOptions $options
     * @return Response
     */
    public function importDocuments($collectionName, $json, ImportOptions $options = null)
    {
        if ($options === null) {
            $options = new ImportOptions();
        }

        $query = [
            'collection' => $collectionName,
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false',
            'complete' => ($options->complete) ? 'true' : 'false',
            'details' => ($options->details) ? 'true' : 'false',
            'type' => $options->type
        ];

        $request = new Request('/_api/import?' . http_build_query($query));
        $request->setMethod(Request::METHOD_POST);
        $request->setBody($json);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string $collectionName
     * @param string $json
     * @param ImportOptions $options
     * @return Response
     */
    public function importHeadersAndValues($collectionName, $json, ImportOptions $options = null)
    {
        if ($options === null) {
            $options = new ImportOptions();
        }

        $query = [
            'collection' => $collectionName,
            'waitForSync' => ($options->waitForSync) ? 'true' : 'false',
            'complete' => ($options->complete) ? 'true' : 'false',
            'details' => ($options->details) ? 'true' : 'false'
        ];

        $request = new Request('/_api/import?' . http_build_query($query));
        $request->setMethod(Request::METHOD_POST);
        $request->setBody($json);

        return $this->client->sendRequest($request);
    }
}