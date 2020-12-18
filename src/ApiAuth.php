<?php

declare(strict_types=1);

namespace Samyan;

class ApiAuth
{
    private $key;
    private $secret;
    private $passphrase;

    /**
     * Constructor
     *
     * @param string $key
     * @param string $secret
     * @param string $passphrase
     */
    public function __construct(string $key, string $secret, string $passphrase)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->passphrase = $passphrase;
    }

    /**
     * Get request headers
     *
     * @param string $method
     * @param string $path
     * @param string $requestParams
     * @param float $timestamp
     * @return array
     */
    public function getRequestHeaders(string $method = 'GET', string $path = '', $requestParams = '', float $timestamp = null): array
    {
        $requestParams = is_array($requestParams) ? json_encode($requestParams) : $requestParams;
        $timestamp = ($timestamp !== null ? $timestamp : time());

        $concatStr = sprintf('%s%s%s%s', $timestamp, strtoupper($method), $path, $requestParams);
        $signature = base64_encode(hash_hmac('sha256', $concatStr, base64_decode($this->secret), true));

        return [
            'CB-ACCESS-KEY' => $this->key,
            'CB-ACCESS-SIGN' => $signature,
            'CB-ACCESS-TIMESTAMP' => $timestamp,
            'CB-ACCESS-PASSPHRASE' => $this->passphrase
        ];
    }
}
