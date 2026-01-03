<?php

namespace Rapltech\LaravelSms\Adapters\Kap;

use GuzzleHttp\Psr7\Request;
use Rapltech\LaravelSms\Adapters\HttpClient;
use Rapltech\LaravelSms\Contracts\AdapterInterface;
use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;

/**
 * KapAdapter
 */
class KapAdapter implements AdapterInterface
{
    use HttpClient;

    /**
     * @var array
     */
    private $config;

    /**
     * Create an instance of Kap Adapter
     * @param array $config configuration for KAP adapter
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->setClient();
    }

    /**
     * {@inheritdoc}
     */
    public function send(Device $device, string $message): ResponseInterface
    {
        $this->checkForMissingConfiguration();

        $response = $this->client->send($this->buildRequest(), $this->buildOptions($device, $message));

        return new KapResponse($device, $response);
    }

    /**
     * Build Guzzle request object
     * @return Request
     */
    private function buildRequest(): Request
    {
        return new Request('GET', 'https://api.kapsystem.com/sms/1/text/query');
    }

    /**
     * Build Guzzle query options with json payload
     * @param Device $device
     * @param string $message
     * @return array
     */
    private function buildOptions(Device $device, string $message): array
    {
        return [
            'debug' => false,
            'verify' => false,
            'timeout' => 20,
            'query' => [
                'username' => $this->config['username'],
                'password' => $this->config['password'],
                'from' => $this->config['sender'],
                'to' => $device->getNumberWithoutPlusSign(),
                'text' => str_replace('\n', '  ', $message),
                'indiaDltTelemarketerId' => $this->config['telemarketer'],
            ],
        ];
    }

    /**
     * Check for valid configuration
     * @throws AdapterException
     */
    private function checkForMissingConfiguration(): void
    {
        $config = $this->config;

        if (!isset($config['username'], $config['password'], $config['sender'], $config['telemarketer'])) {
            throw AdapterException::missingConfiguration();
        }
    }
}
