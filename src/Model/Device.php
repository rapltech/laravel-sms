<?php

namespace Rapltech\LaravelSms\Model;

/**
 * Device class
 */
class Device
{
    /**
     * Mobile number (E.164 format)
     * @var string
     */
    private $number;

    /**
     * Country iso code
     * @var string
     */
    private $country_iso;

    /**
     * Create an instance of Device
     *
     * @param string $number
     * @param string $country_iso
     */
    public function __construct(string $number, string $country_iso)
    {
        $this->number = $number;
        $this->country_iso = strtoupper($country_iso);
    }

    /**
     * Get device number
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get country iso for the device number
     * @return string
     */
    public function getCountryIso(): string
    {
        return $this->country_iso;
    }

    /**
     * Get device number without plus sign
     * @return string
     */
    public function getNumberWithoutPlusSign(): string
    {
        return ltrim($this->getNumber(), '+');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
