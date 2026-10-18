<?php

namespace Linkstreet\LaravelSms\Tests\Adapters;

trait HttpClient
{
    /**
     * Mock http client
     * @param int|\Throwable $result HTTP status code or exception to queue
     * @param array $response
     * @return \GuzzleHttp\Client
     */
    public function mockClient(int|\Throwable $result, array $response = [])
    {
        $result = $result instanceof \Throwable
            ? $result
            : new \GuzzleHttp\Psr7\Response(
                $result,
                ['Content-type' => 'application/json'],
                json_encode($response)
            );

        $handler = \GuzzleHttp\HandlerStack::create(
            new \GuzzleHttp\Handler\MockHandler([$result])
        );

        return new \GuzzleHttp\Client(['handler' => $handler]);
    }
}
