<?php

namespace Rapltech\LaravelSms\Adapters;

use GuzzleHttp\Client;

trait HttpClient
{
    protected $client;

    /**
     * @param Client|null $client
     * @return self
     */
    public function setClient($client = null)
    {
        $this->client = $client ?? new Client();

        return $this;
    }
}
