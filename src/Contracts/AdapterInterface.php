<?php

namespace Rapltech\LaravelSms\Contracts;

use Rapltech\LaravelSms\Model\Device;

/**
 * AdapterInterface.
 */
interface AdapterInterface
{
    /**
     * Send SMS
     * @param \Rapltech\LaravelSms\Model\Device $device
     * @param string $message
     * @return ResponseInterface
     */
    public function send(Device $device, string $message): ResponseInterface;
}
