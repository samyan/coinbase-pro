<?php

declare(strict_types=1);

namespace Samyan;

use GuzzleHttp\Client;

class HttpClient
{
    const ARGS_TYPE = 0;
    const QUERY_TYPE = 1;
    const RAW_JSON_TYPE = 2;

    private $prodEndpoint = 'https://api.pro.coinbase.com';
    private $devEndpoint = 'https://api-public.sandbox.pro.coinbase.com';

    private $displayHttpErrors;

    private $auth;
    private $endpoint;

    /**
     * Constructor
     *
     * @param ApiAuth $auth
     * @param boolean $test
     * @param boolean $displayHttpErrors
     */
    public function __construct(ApiAuth $auth, bool $test = false, bool $displayHttpErrors = false)
    {
        $this->displayHttpErrors = $displayHttpErrors;
        $this->auth = $auth;
        $this->endpoint = $test ? $this->devEndpoint : $this->prodEndpoint;
    }

    /**
     * Request
     *
     * @param string $method
     * @param string $path
     * @param array $params
     * @param integer $paramType
     * @param float $timestamp
     * @param boolean $secure
     * @return string
     */
    public function request(string $method, string $path, array $params = [], int $paramType = null, float $timestamp = null, bool $secure = false): string
    {
        $requestParams = [];
        $hashParams = null;
        $clientOptions = ['http_errors' => $this->displayHttpErrors];

        // Building final url
        $url = sprintf('%s%s', $this->endpoint, $path);

        switch ($paramType) {
            case self::ARGS_TYPE: // Looking for argument params
                $requestParams = ''; // Explicit cast to string

                foreach ($params as $index => $param) {
                    if ($index < count($params)) {
                        $requestParams .= '/' . $param;
                    }
                }

                $hashParams = $requestParams;

                $url .= $requestParams; // Adding the argument to url
                $requestParams = []; // Explicit cast to array

                break;
            case self::QUERY_TYPE: // Looking for query params
                $requestParams['query'] = $params;
                $hashParams = '?' . http_build_query($requestParams['query']);

                break;
            case self::RAW_JSON_TYPE: // Looking for body params
                $requestParams['json'] = $params;
                $hashParams = $requestParams['json'];
        }

        // If private api then we attach required headers
        if ($secure) {
            $headers = $this->auth->getRequestHeaders($method, $path, $hashParams, $timestamp);

            $clientOptions['headers'] = $headers;
        }

        $client = new Client($clientOptions);
        $response = $client->request($method, $url, $requestParams);
        $body = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            throw new CoinbaseException($body, $response->getStatusCode());
        }

        return $body;
    }
}
