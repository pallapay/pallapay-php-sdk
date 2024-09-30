<?php

namespace Pallapay\PallapaySDK;


class IpnData
{
    private const PAID_STATUS = 'PAID';

    /**
     * @var string $secretKey
     */
    private string $secretKey;

    /**
     * @var string $approvalHash
     */
    private string $approvalHash;

    /**
     * @var string $paymentRequestId
     */
    private string $paymentRequestId;

    /**
     * @var string $paymentAmount
     */
    private string $paymentAmount;

    /**
     * @var string $paymentCurrency
     */
    private string $paymentCurrency;

    /**
     * @var string|null $receivingAmount
     */
    private ?string $receivingAmount;

    /**
     * @var string|null $receivingCurrency
     */
    private ?string $receivingCurrency;

    /**
     * @var string|null $paidCryptocurrency
     */
    private ?string $paidCryptocurrency;

    /**
     * @var string|null $feeAmount
     */
    private ?string $feeAmount;

    /**
     * @var string|null $feePaidBy
     */
    private ?string $feePaidBy;

    /**
     * @var string $payerEmailAddress
     */
    private string $payerEmailAddress;

    /**
     * @var string|null $payerFirstName
     */
    private ?string $payerFirstName;

    /**
     * @var string|null $payerLastName
     */
    private ?string $payerLastName;

    /**
     * @var string|null $refId
     */
    private ?string $refId;

    /**
     * @var string $status
     */
    private string $status;

    /**
     * @var string|null $paidAt
     */
    private ?string $paidAt;

    /**
     * @var string|null $note
     */
    private ?string $note;

    /**
     * @var string|null $orderId
     */
    private ?string $orderId;

    /**
     * @param string $secretKey
     * @param string $approvalHash
     * @param string $paymentRequestId
     * @param string $paymentAmount
     * @param string $paymentCurrency
     * @param string $payerEmailAddress
     * @param string $status
     * @param string|null $receivingAmount
     * @param string|null $receivingCurrency
     * @param string|null $paidCryptocurrency
     * @param string|null $feeAmount
     * @param string|null $feePaidBy
     * @param string|null $payerFirstName
     * @param string|null $payerLastName
     * @param string|null $refId
     * @param string|null $paidAt
     * @param string|null $note
     * @param string|null $orderId
     */
    function __construct(
        string $secretKey,
        string $approvalHash,
        string $paymentRequestId,
        string $paymentAmount,
        string $paymentCurrency,
        string $payerEmailAddress,
        string $status,
        ?string $receivingAmount,
        ?string $receivingCurrency,
        ?string $paidCryptocurrency,
        ?string $feeAmount,
        ?string $feePaidBy,
        ?string $payerFirstName,
        ?string $payerLastName,
        ?string $refId,
        ?string $paidAt,
        ?string $note,
        ?string $orderId
    ) {
        $this->secretKey = $secretKey;
        $this->approvalHash = $approvalHash;
        $this->paymentRequestId = $paymentRequestId;
        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrency = $paymentCurrency;
        $this->payerEmailAddress = $payerEmailAddress;
        $this->status = $status;
        $this->receivingAmount = $receivingAmount;
        $this->receivingCurrency = $receivingCurrency;
        $this->paidCryptocurrency = $paidCryptocurrency;
        $this->feeAmount = $feeAmount;
        $this->feePaidBy = $feePaidBy;
        $this->payerFirstName = $payerFirstName;
        $this->payerLastName = $payerLastName;
        $this->refId = $refId;
        $this->paidAt = $paidAt;
        $this->note = $note;
        $this->orderId = $orderId;
    }

    /**
     * @return bool
     */
    public function isValid(): bool {
        $data = $this->getAll();
        ksort($data);

        $approvalString = implode('', $data);
        $approvalHash = hash_hmac('sha256', $approvalString, $this->secretKey);

        return $this->approvalHash == $approvalHash;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool {
        return $this->status == self::PAID_STATUS;
    }

    /**
     * @return array
     */
    public function getAll(): array {
        return [
            'paymentRequestId' => $this->paymentRequestId,
            'paymentAmount' => $this->paymentAmount,
            'paymentCurrency' => $this->paymentCurrency,
            'payerEmailAddress' => $this->payerEmailAddress,
            'status' => $this->status,
            'receivingAmount' => $this->receivingAmount,
            'receivingCurrency' => $this->receivingCurrency,
            'paidCryptocurrency' => $this->paidCryptocurrency,
            'feeAmount' => $this->feeAmount,
            'feePaidBy' => $this->feePaidBy,
            'payerFirstName' => $this->payerFirstName,
            'payerLastName' => $this->payerLastName,
            'refId' => $this->refId,
            'paidAt' => $this->paidAt,
            'note' => $this->note,
        ];
    }


    /**
     * @return string
     */
    public function getPaymentRequestId(): string {
        return $this->paymentRequestId;
    }

    /**
     * @return string
     */
    public function getPaymentAmount(): string {
        return $this->paymentAmount;
    }

    /**
     * @return string
     */
    public function getPaymentCurrency(): string {
        return $this->paymentCurrency;
    }

    /**
     * @return string
     */
    public function getPayerEmailAddress(): string {
        return $this->payerEmailAddress;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getReceivingAmount(): ?string {
        return $this->receivingAmount;
    }


    /**
     * @return string|null
     */
    public function getReceivingCurrency(): ?string {
        return $this->receivingCurrency;
    }

    /**
     * @return string|null
     */
    public function getPaidCryptocurrency(): ?string {
        return $this->paidCryptocurrency;
    }

    /**
     * @return string|null
     */
    public function getFeeAmount(): ?string {
        return $this->feeAmount;
    }

    /**
     * @return string|null
     */
    public function getFeePaidBy(): ?string {
        return $this->feePaidBy;
    }

    /**
     * @return string|null
     */
    public function getPayerFirstName(): ?string {
        return $this->payerFirstName;
    }

    /**
     * @return string|null
     */
    public function getPayerLastName(): ?string {
        return $this->payerLastName;
    }

    /**
     * @return string|null
     */
    public function getRefId(): ?string {
        return $this->refId;
    }

    /**
     * @return string|null
     */
    public function getPaidAt(): ?string {
        return $this->paidAt;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string {
        return $this->note;
    }

    /**
     * @return string|null
     */
    public function getOrderId(): ?string {
        return $this->orderId;
    }
}
