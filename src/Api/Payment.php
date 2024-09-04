<?php

namespace Pallapay\PallapaySDK\Api;

use GuzzleHttp\Exception\GuzzleException;
use Pallapay\PallapaySDK\Exceptions\ApiException;
use Pallapay\PallapaySDK\Exceptions\IpnException;
use Pallapay\PallapaySDK\IpnData;
use Pallapay\PallapaySDK\Request;

class Payment extends Request
{
    public function __construct(string $apiKey, string $secretKey, string $host)
    {
        $this->setHost($host);
        $this->setApiKey($apiKey);
        $this->setSecretKey($secretKey);
    }

    /**
     * @param string $symbol
     * @param string $amount
     * @param string $payerEmailAddress
     * @param string $ipnSuccessUrl
     * @param string $ipnFailedUrl
     * @param string|null $webhookUrl
     * @param string|null $payerFirstName
     * @param string|null $payerLastName
     * @param string|null $note
     * @param string|null $orderId
     * @return mixed
     * @throws ApiException
     */
    public function create(
        string $symbol,
        string $amount,
        string $payerEmailAddress,
        string $ipnSuccessUrl,
        string $ipnFailedUrl,
        ?string $webhookUrl,
        ?string $payerFirstName,
        ?string $payerLastName,
        ?string $note,
        ?string $orderId
    ) {

        $this->addJsonParam('symbol', $symbol);
        $this->addJsonParam('amount', $amount);
        $this->addJsonParam('payer_email_address', $payerEmailAddress);
        $this->addJsonParam('ipn_success_url', $ipnSuccessUrl);
        $this->addJsonParam('ipn_failed_url', $ipnFailedUrl);
        $this->addJsonParam('webhook_url', $webhookUrl);
        $this->addJsonParam('payer_first_name', $payerFirstName);
        $this->addJsonParam('payer_last_name', $payerLastName);
        $this->addJsonParam('note', $note);
        $this->addJsonParam('order_id', $orderId);

        return $this->sendRequest(self::POST_METHOD, '/api/v1/api/payments');
    }

    /**
     * @param int $page
     * @return mixed
     * @throws ApiException
     */
    public function getAll(int $page = 1) {
        $this->addQueryParam('page', $page);
        return $this->sendRequest(self::GET_METHOD, '/api/v1/api/payments');
    }

    /**
     * @param string $paymentRequestId
     * @return mixed
     * @throws ApiException
     */
    public function getByPaymentRequestId(string $paymentRequestId) {
        if ($paymentRequestId == "") {
            Throw new ApiException("Invalid payment request ID");
        }

        return $this->sendRequest(self::GET_METHOD, "/api/v1/api/payments/{$paymentRequestId}");
    }

    /**
     * @param array $requestData
     * @return IpnData
     * @throws IpnException
     */
    public function getIpnData(array $requestData): IpnData {
        try {
            $data = $requestData['data'];
            $approvalHash = $requestData['approval_hash'];

            return new IpnData(
                $this->secretKey,
                $approvalHash,
                $data['payment_request_id'],
                $data['payment_amount'],
                $data['payment_currency'],
                $data['payer_email_address'],
                $data['status'],
                $data['receiving_amount'],
                $data['paid_cryptocurrency'],
                $data['fee_amount'],
                $data['fee_paid_by'],
                $data['payer_first_name'],
                $data['payer_last_name'],
                $data['ref_id'],
                $data['paid_at'],
                $data['note'],
                $data['order_id'],
            );
        } catch (\Exception $e) {
            throw new IpnException("Ipn request data error", 0, $e);
        }
    }
}
