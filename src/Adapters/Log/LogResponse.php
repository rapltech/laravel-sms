<?php

namespace Rapltech\LaravelSms\Adapters\Log;

use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Model\Device;

class LogResponse implements ResponseInterface
{
    private Device $device;
    private string $message;

    public function __construct(Device $device, string $message)
    {
        $this->device = $device;
        $this->message = $message;
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    public function getResponse()
    {
        return (object)[
            'device' => $this->device->toArray(),
            'message' => $this->message,
            'debug' => 'Mocked success response',
        ];
    }

    public function getRaw()
    {
        return $this;
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }

    public function getErrorCode()
    {
        return '';
    }

    public function getErrorMessage()
    {
        return '';
    }

    public function getReasonPhrase()
    {
        return '';
    }
}
