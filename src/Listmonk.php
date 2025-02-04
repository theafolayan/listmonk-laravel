<?php

namespace Theafolayan\ListmonkLaravel;

use Theafolayan\ListmonkLaravel\Http\Client;

class Listmonk
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getSubscribers($filters = [])
    {
        return $this->client->request('GET', '/api/subscribers', $filters);
    }

    public function createSubscriber($data)
    {
        return $this->client->request('POST', '/api/subscribers', $data);
    }

    public function getLists()
    {
        return $this->client->request('GET', '/api/lists');
    }

    public function createList($data)
    {
        return $this->client->request('POST', '/api/lists', $data);
    }
}
