<?php

declare(strict_types=1);

namespace Samyan;

class Coinbase
{
    private $httpClient;

    /**
     * Constructor
     *
     * @param ApiAuth $auth
     * @param boolean $test
     * @param boolean $displayHttpErrors
     */
    public function __construct(ApiAuth $auth, bool $test = false, bool $displayHttpErrors = false)
    {
        $this->httpClient = new HttpClient($auth, $test, $displayHttpErrors);
    }

    /**
     * Get server time
     *
     * @param boolean $decode
     * @return object|string
     */
    public function getServerTime(bool $decode = false)
    {
        $result = $this->httpClient->request('GET', '/time', [], null, null, false);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * List account
     *
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function listAccounts(bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $result = $this->httpClient->request('GET', '/accounts', [], null, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * Get account
     *
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function getAccount(string $accountId, bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $params = [$accountId];

        $result = $this->httpClient->request('GET', '/accounts', $params, HttpClient::ARGS_TYPE, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * Get current exchange limits
     *
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return void
     */
    public function getCurrentExchangeLimits(bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $result = $this->httpClient->request('GET', '/users/self/exchange-limits', [], null, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }


    /**
     * List withdrawals
     *
     * @param string $profileId
     * @param integer $before
     * @param integer $after
     * @param integer $limit
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function listWithdrawals(string $profileId = '', int $before = null, int $after = null, int $limit = 100, bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $params = [
            'type' => 'withdraw',
            'profile_id' => $profileId,
            'before' => $before,
            'after' => $after,
            'limit' => $limit
        ];

        $result = $this->httpClient->request('GET', '/transfers', $params, HttpClient::QUERY_TYPE, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * Get withdrawal
     *
     * @param string $tranferId
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function getWithdrawal(string $tranferId, bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $params = [$tranferId];

        $result = $this->httpClient->request('GET', '/transfers', $params, HttpClient::ARGS_TYPE, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * Withdraw
     *
     * @param string $amount
     * @param string $currency
     * @param string $address
     * @param string $addressTag
     * @param boolean $addNetworkFeeToTotal
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function withdrawal(string $amount, string $currency, string $address, string $addressTag = '', bool $addNetworkFeeToTotal = false, bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $params = [
            'amount' => $amount,
            'currency' => $currency,
            'crypto_address' => $address,
            'destination_tag' => $addressTag,
            'no_destination_tag' => strlen($addressTag) === 0,
            'add_network_fee_to_total' => $addNetworkFeeToTotal
        ];

        $result = $this->httpClient->request('POST', '/withdrawals/crypto', $params, HttpClient::RAW_JSON_TYPE, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }

    /**
     * Get fee estimate
     *
     * @param boolean $syncServerTime
     * @param boolean $decode
     * @return object|string
     */
    public function getFeeEstimate(string $currency, string $address, bool $syncServerTime = false, bool $decode = false)
    {
        $timestamp = null;

        if ($syncServerTime) {
            $result = $this->getServerTime(true);

            $timestamp = $result->epoch;
        }

        $params = [
            'currency' => $currency,
            'crypto_address' => $address
        ];

        $result = $this->httpClient->request('GET', '/withdrawals/fee-estimate', $params, HttpClient::QUERY_TYPE, $timestamp, true);

        return $decode ? json_decode($result) : $result;
    }
}
