<?php

namespace Rapltech\LaravelSms\Adapters\Log;

use Illuminate\Support\Facades\Log;
use Rapltech\LaravelSms\Contracts\AdapterInterface;
use Rapltech\LaravelSms\Contracts\ResponseInterface;
use Rapltech\LaravelSms\Model\Device;

class LogAdapter implements AdapterInterface
{
    public function send(Device $device, string $message): ResponseInterface
    {
        Log::debug('SMS', [
            'device' => $device->toArray(),
            'message' => $message,
        ]);

        return new LogResponse($device, $message);
    }
}
