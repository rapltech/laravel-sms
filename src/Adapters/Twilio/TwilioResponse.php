<?php

namespace Rapltech\LaravelSms\Adapters\Twilio;

use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Exceptions\AdapterException;
use Rapltech\LaravelSms\Model\Device;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Twilio Response
 */
class TwilioResponse implements ResponseInterface
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
     * @param  $response
     * @throws AdapterException
     */
    public function __construct(Device $device, $response)
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
        return $this->response->getStatusCode() < 400;
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
        return ($this->response->getStatusCode() < 400)
            ? $this->refined->error_code
            : $this->refined->code;
    }

    /**
     * Error message if available
     * @return string
     */
    public function getErrorMessage()
    {
        return ($this->response->getStatusCode() < 400)
            ? $this->refined->error_message
            : $this->refined->message;
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

        return $result;
    }
}
