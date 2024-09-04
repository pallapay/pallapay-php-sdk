<?php

namespace Pallapay\PallapaySDK;

use Pallapay\PallapaySDK\Api\Balance;
use Pallapay\PallapaySDK\Api\Payment;

class PallapayClient
{
    /**
     * @var string $host
     */
    protected string $host;

    /**
     * @var string $apiKey
     */
    protected string $apiKey;

    /**
     * @var string $secretKey
     */
    protected string $secretKey;

    /**
     * @param string $apiKey
     * @param string $secretKey
     * @param string $host
     */
    function __construct(string $apiKey, string $secretKey, string $host = 'https://app.pallapay.com'){
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @return Payment
     */
    public function payment(): Payment
    {
        return new Payment($this->apiKey, $this->secretKey, $this->host);
    }

    /**
     * @return Balance
     */
    public function balance(): Balance
    {
        return new Balance($this->apiKey, $this->secretKey, $this->host);
    }
}
