<?php

namespace Rapltech\LaravelSms\Contracts;

/**
 * ResponseInterface.
 */
interface ResponseInterface
{
    /**
     * Get the response status code
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Get processed response body
     * @return mixed
     */
    public function getResponse();

    /**
     * Get raw response
     * @return mixed
     */
    public function getRaw();

    /**
     * Is success response
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Is failure response
     * @return bool
     */
    public function isFailure(): bool;

    /**
     * Error code if available
     * @return string|null
     */
    public function getErrorCode();

    /**
     * Error message if available
     * @return string|null
     */
    public function getErrorMessage();

    /**
     * Get success/failure response message
     * @return string|null
     */
    public function getReasonPhrase();
}
