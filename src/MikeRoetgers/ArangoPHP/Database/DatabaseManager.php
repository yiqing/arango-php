<?php

namespace MikeRoetgers\ArangoPHP\Database;

use MikeRoetgers\ArangoPHP\Database\Exception\UnknownDatabaseException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Client;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\ConflictException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\InvalidRequestException;
use MikeRoetgers\ArangoPHP\HTTP\Client\Exception\UnexpectedStatusCodeException;
use MikeRoetgers\ArangoPHP\HTTP\Request;
use MikeRoetgers\ArangoPHP\User\User;
use MikeRoetgers\ArangoPHP\User\UserMapper;

class DatabaseManager
{
    /**
     * @var DatabaseMapper
     */
    private $databaseMapper;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * @var Client
     */
    private $client;

    /**
     * @return Database
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     * @throws Exception\UnknownDatabaseException
     */
    public function getCurrentDatabase()
    {
        $request = new Request('/_api/database/current');
        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->databaseMapper->mapArrayToEntity($response->getBodyAsArray()['result']);
                break;
            case 400:
                throw new InvalidRequestException();
                break;
            case 404:
                throw new UnknownDatabaseException();
                break;
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @return array
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     */
    public function getAllAccessibleDatabases()
    {
        $request = new Request('/_api/database/user');
        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $response->getBodyAsArray()['result'];
                break;
            case 400:
                throw new InvalidRequestException();
                break;
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     * @throws \DomainException
     * @return array
     */
    public function getAllDatabases()
    {
        $request = new Request('/_api/database');
        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $response->getBodyAsArray()['result'];
                break;
            case 400:
                throw new InvalidRequestException();
                break;
            case 403:
                throw new \DomainException('Request must be executed in the _system database');
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @param User[] $users
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     * @throws ConflictException
     * @throws \DomainException
     * @return bool
     */
    public function createDatabase($name, array $users = array())
    {
        $userData = array();
        foreach ($users as $user) {
            $userData[] = $this->userMapper->mapEntityToArray($user);
        }

        $bodyData = array(
            'name' => $name,
            'users' => $users
        );

        $request = new Request('/_api/database', Request::METHOD_POST);
        $request->setBody(json_encode($bodyData));
        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return (bool)$response->getBodyAsArray()['result'];
            case 400:
                throw new InvalidRequestException();
            case 403:
                throw new \DomainException('Request must be executed in the _system database');
            case 409:
                throw new ConflictException('A database with the name "' . $name . '" already exists.');
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }

    /**
     * @param string $name
     * @return bool
     * @throws InvalidRequestException
     * @throws UnexpectedStatusCodeException
     * @throws Exception\UnknownDatabaseException
     * @throws \DomainException
     */
    public function deleteDatabase($name)
    {
        $request = new Request('/_api/database/' . $name, Request::METHOD_DELETE);
        $response = $this->client->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                return (bool)$response->getBodyAsArray()['result'];
            case 400:
                throw new InvalidRequestException();
            case 403:
                throw new \DomainException('Request must be executed in the _system database');
            case 404:
                throw new UnknownDatabaseException();
            default:
                throw new UnexpectedStatusCodeException($response);
        }
    }
}