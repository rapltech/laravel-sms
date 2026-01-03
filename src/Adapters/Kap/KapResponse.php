<?php

namespace Rapltech\LaravelSms\Adapters\Kap;

use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * KAP Response
 */
class KapResponse implements ResponseInterface
{
    /**
     * @var Device
     */
    private $device;

    /**
     * @var PsrResponseInterface
     */
    private $response;

    /**
     * @var \stdClass
     */
    private $refined;

    /**
     * KapResponse constructor.
     * @param Device $device
     * @param PsrResponseInterface $response
     * @throws AdapterException
     */
    public function __construct(Device $device, PsrResponseInterface $response)
    {
        $this->device = $device;
        $this->response = $response;
        $this->refined = $this->processResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get processed response body
     * @return \stdClass
     */
    public function getResponse()
    {
        return $this->refined;
    }

    /**
     * {@inheritdoc}
     * @return PsrResponseInterface
     */
    public function getRaw()
    {
        return $this->response;
    }

    /**
     * Is success response
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getErrorCode() == 0;
    }

    /**
     * Is failure response
     * @return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * Error code if available
     * @return string
     */
    public function getErrorCode()
    {
        return $this->refined->status;
    }

    /**
     * Error message if available
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->statusDescription()[$this->getErrorCode()] ?? '';
    }

    /**
     * Get success/failure response message
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * Process the given response
     * @throws AdapterException
     */
    private function processResponse(): \stdClass
    {
        $content = $this->response->getBody();

        $result = json_decode($content);

        if (JSON_ERROR_NONE !== $error = json_last_error()) {
            throw AdapterException::responseParseError($error, $content);
        }

        if (!isset($result->messages) || !is_array($result->messages)) {
            throw AdapterException::responseParseError('Invalid response structure', $content);
        }

        return reset($result->messages);
    }

    /**
     * List of response status descriptions
     * @return array
     */
    private function statusDescription(): array
    {
        return [
            0 => 'Request was successful',
            -1 => 'Error in processing the request',
            -2 => 'Not enough credits on a specific account',
            -3 => 'Targeted network is not covered on specific account',
            -5 => 'Username or password is invalid',
            -6 => 'Destination address is missing in the request',
            -10 => 'Username is missing in the request',
            -11 => 'Password is missing in the request',
            -13 => 'Number is not recognized by the platform',
            -22 => 'Incorrect XML format, caused by syntax error',
            -23 => 'General error, reasons may vary',
            -26 => 'General API error, reasons may vary',
            -27 => 'Invalid scheduling parameter',
            -28 => 'Invalid PushURL in the request',
            -30 => 'Invalid APPID in the request',
            -33 => 'Duplicated MessageID in the request',
            -34 => 'Sender name is not allowed',
            -99 => 'Error in processing request, reasons may vary',
        ];
    }
}
