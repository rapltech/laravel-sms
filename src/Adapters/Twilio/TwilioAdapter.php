<?php

namespace Rapltech\LaravelSms\Adapters\Twilio;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Rapltech\LaravelSms\Adapters\HttpClient;
use Rapltech\LaravelSms\Contracts\AdapterInterface;
use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;

/**
 * TwilioAdapter
 */
class TwilioAdapter implements AdapterInterface
{
    use HttpClient;

    /**
     * @var array
     */
    private $config;

    /**
     * Create a instance for Adapter
     * @param array configuration for adapter
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

        try {
            $response = $this->client->send($this->buildRequest(), $this->buildOptions($device, $message));
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }

        return new TwilioResponse($device, $response);
    }

    /**
     * Build Guzzle request object
     * @return Request
     */
    private function buildRequest(): Request
    {
        return new Request(
            'POST',
            "https://api.twilio.com/2010-04-01/Accounts/{$this->config['sid']}/Messages.json"
        );
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
            'auth' => [
                $this->config['sid'],
                $this->config['token']
            ],
            'form_params' => [
                'Body' => $message,
                'From' => $this->config['from'],
                'To' => $device->getNumber(),
            ]
        ];
    }

    /**
     * Check for valid configuration
     * @throws AdapterException
     */
    private function checkForMissingConfiguration()
    {
        $config = $this->config;

        if (!isset($config['sid'], $config['token'], $config['from'])) {
            throw AdapterException::missingConfiguration();
        }
    }
}
