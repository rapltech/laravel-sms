<?php

namespace Rapltech\LaravelSms\Adapters;

use Rapltech\LaravelSms\Exceptions\AdapterException;

class Adapter
{
    /**
     * List of adapter implementations
     *
     * @var array
     */
    private static $list = [
        'twilio' => Twilio\TwilioAdapter::class,
        'kap' => Kap\KapAdapter::class,
    ];

    /**
     * @param string $adapter
     * @return mixed|static
     * @throws AdapterException
     */
    public static function find(string $adapter)
    {
        return isset(self::$list[$adapter])
            ? self::$list[$adapter]
            : AdapterException::notFound($adapter);
    }

    /**
     * @return array
     */
    public static function all()
    {
        return self::$list;
    }
}
