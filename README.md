# Coinbase PRO Client for PHP
[![Latest Stable Version](https://poser.pugx.org/samyan/coinbase-pro/v)](//packagist.org/packages/samyan/coinbase-pro) [![Total Downloads](https://poser.pugx.org/samyan/coinbase-pro/downloads)](//packagist.org/packages/samyan/coinbase-pro) [![License](https://poser.pugx.org/samyan/coinbase-pro/license)](//packagist.org/packages/samyan/coinbase-pro)


Coinbase Pro basic client for **PHP 7.0+**

## Installation

Require the package in `composer.json`

```json
"require": {
    "samyan/coinbase-pro": "1.*"
},
```
## Basic usage

```php
use Samyan\ApiAuth;
use Samyan\Coinbase;

$auth = new ApiAuth('YOUR_API_KEY', 'YOUR_SECRET_KEY', 'YOUR_PASSPHRASE');
$coinbaseClient = new Coinbase($auth, false, false);

$result = $coinbaseClient->withdrawal('0.05', 'BTC', 'bc1qzep2wle7f9ane6aa2kchvnu5r2z4340h09ypx7');
```

## List of implemented API

* getServerTime
* listAccounts
* getAccount
* getCurrentExchangeLimits
* listWithdrawals
* getWithdrawal
* withdrawal
* getFeeEstimate