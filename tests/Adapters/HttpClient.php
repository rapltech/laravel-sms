<?php

namespace Rapltech\LaravelSms\Tests\Adapters;

trait HttpClient
{
    /**
     * Mock http client
     * @param int $status
     * @param array $response
     * @return \GuzzleHttp\Client
     */
    public function mockClient(int $status, array $response = [])
    {
        $handler = \GuzzleHttp\HandlerStack::create(
            new \GuzzleHttp\Handler\MockHandler([
                new \GuzzleHttp\Psr7\Response(
                    $status,
                    ['Content-type' => 'application/json'],
                    json_encode($response)
                )
            ])
        );

        return new \GuzzleHttp\Client(['handler' => $handler]);
    }
}
