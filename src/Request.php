<?php

namespace Pallapay\PallapaySDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Pallapay\PallapaySDK\Exceptions\ApiException;

class Request
{
    private const DEFAULT_TIMEOUT = 60;

    protected const GET_METHOD = 'GET';
    protected const POST_METHOD = 'POST';
    protected const PATCH_METHOD = 'PATCH';
    protected const PUT_METHOD = 'PUT';
    protected const DELETE_METHOD = 'DELETE';

    /**
     * @var array $options
     */
    private array $options = [];

    /**
     * @var array $headers
     */
    private array $headers = [];

    /**
     * @var array $jsonParams
     */
    private array $jsonParams = [];

    /**
     * @var array $queryParams
     */
    private array $queryParams = [];

    /**
     * @var string $host
     */
    protected string $host = '';

    /**
     * @var string $apiKey
     */
    protected string $apiKey;

    /**
     * @var string $secretKey
     */
    protected string $secretKey;


    /**
     * @param string $host
     * @return void
     */
    protected function setHost(string $host): void {
        $this->host = $host;
    }


    /**
     * @param string $apiKey
     * @return void
     */
    protected function setApiKey(string $apiKey): void {
        $this->apiKey = $apiKey;
    }


    /**
     * @param string $secretKey
     * @return void
     */
    protected function setSecretKey(string $secretKey): void {
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $optionName
     * @param $optionValue
     * @return void
     */
    protected function setOption(string $optionName, $optionValue = null): void {
        if ($optionValue !== [] && $optionValue != null) {
            $this->options[$optionName] = $optionValue;
        }
    }

    /**
     * @param string $headerName
     * @param $headerValue
     * @return void
     */
    protected function addHeader(string $headerName, $headerValue): void {
        $this->headers[$headerName] = $headerValue;
    }

    /**
     * @param string $jsonParamName
     * @param $jsonParamValue
     * @return void
     */
    protected function addJsonParam(string $jsonParamName, $jsonParamValue): void {
        $this->jsonParams[$jsonParamName] = $jsonParamValue;
    }

    /**
     * @param string $queryParamName
     * @param $queryParamValue
     * @return void
     */
    protected function addQueryParam(string $queryParamName, $queryParamValue): void {
        $this->queryParams[$queryParamName] = $queryParamValue;
    }

    /**
     * @param string $method
     * @param string $path
     * @return mixed
     * @throws ApiException
     * @throws GuzzleException
     */
    protected function sendRequest(string $method, string $path) {
        $timestamp = time();
        $approvalString = $method . $path . $timestamp;
        $signature = hash_hmac('sha256', $approvalString, $this->secretKey);

        $this->addHeader('X-Palla-Api-Key', $this->apiKey);
        $this->addHeader('X-Palla-Sign', $signature);
        $this->addHeader('X-Palla-Timestamp', $timestamp);

        $this->setOption('headers', $this->headers);
        $this->setOption('json', $this->jsonParams);
        $this->setOption('query', $this->queryParams);
        $this->setOption('timeout', $this->options['timeout'] ?? self::DEFAULT_TIMEOUT);

        try {
            $client = new Client();

            $response = $client->request($method, $this->host . $path, $this->options);
            $decodedResponse = json_decode($response->getBody()->getContents(), true);

            if (!is_array($decodedResponse) || !isset($decodedResponse['is_successful']) || !$decodedResponse['is_successful'] || !isset($decodedResponse['data']))
                Throw new ApiException("Pallapay invalid response. method: ($method) url: ($this->host$path) Response:", $decodedResponse);

            return $decodedResponse;
        } catch (RequestException $e){
            if (method_exists($e->getResponse(),'getBody')){
                $contents = $e->getResponse()->getBody()->getContents();

                $temp = json_decode($contents, true);
                if (!empty($temp)) {
                    $temp['_method'] = $method;
                    $temp['_url'] = $this->host . $path;
                } else {
                    $temp['_message'] = $e->getMessage();
                }
            } else {
                $temp['_message'] = $e->getMessage();
            }
            $temp['_httpcode'] = $e->getCode();

            throw new ApiException(json_encode($temp));
        }
    }
}
