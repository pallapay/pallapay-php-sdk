<?php

namespace Pallapay\PallapaySDK\Api;

use GuzzleHttp\Exception\GuzzleException;
use Pallapay\PallapaySDK\Exceptions\ApiException;
use Pallapay\PallapaySDK\Request;

class Balance extends Request
{
    public function __construct(string $apiKey, string $secretKey, string $host)
    {
        $this->setHost($host);
        $this->setApiKey($apiKey);
        $this->setSecretKey($secretKey);
    }

    /**
     * @return mixed
     * @throws ApiException
     */
    public function getAll() {
        return $this->sendRequest(self::GET_METHOD, '/api/v1/api/balances');
    }

    /**
     * @param string $symbol
     * @return mixed
     * @throws ApiException
     */
    public function getBySymbol(string $symbol) {
        if ($symbol == "") {
            Throw new ApiException("Invalid symbol");
        }

        return $this->sendRequest(self::GET_METHOD, "/api/v1/api/balances/{$symbol}");
    }
}
