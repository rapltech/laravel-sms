<?php

namespace Rapltech\LaravelSms\Exceptions;

class DeviceException extends \Exception
{
    /**
     * Device not found
     * @return static
     */
    public static function notFound()
    {
        return new static('Device information not found');
    }
}
