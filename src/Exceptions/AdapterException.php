<?php

namespace Rapltech\LaravelSms\Exceptions;

use Rapltech\LaravelSms\Model\Device;

class AdapterException extends \Exception
{
    /**
     * Adapter not found
     * @param string $adapter
     * @return static
     */
    public static function notFound(string $adapter)
    {
        return new static("Adapter not found - $adapter");
    }

    /**
     * Unknown connection string
     * @param string $connection
     * @return static
     */
    public static function unknownConnection(string $connection)
    {
        return new static("Unknown connection string - $connection");
    }

    /**
     * No possible connection available
     * @param Device $device
     * @return static
     */
    public static function noPossibleConnection(Device $device)
    {
        return new static("No possible connection available for this device {$device->getNumber()}");
    }

    /**
     * Missing configuration for adapter
     * @return static
     */
    public static function missingConfiguration()
    {
        return new static('Some configurations are missing for this adapter');
    }

    /**
     * Response parse error
     * @param string $error
     * @param mixed $content
     * @return static
     */
    public static function responseParseError(string $error, $content)
    {
        return new static("Response parse error - $error - (string)$content");
    }
}
