## Pallapay crypto payment gateway SDK

Easy to use SDK for pallapay crypto payment gateway, accept crypto in your website and get paid in cash.

#### Installation
```
composer require pallapay/pallapay-php-sdk
```

#### Easy to use

First signup, create API Key and [get you ApiKey, SecretKey from Pallapay website](https://www.pallapay.com)

Then you can create a payment:

```php
use Pallapay\PallapaySDK\PallapayClient;

$apiKey = "YOUR_API_KEY";
$secretKey = "YOUR_SECRET_KEY";
$pallapayClient = new PallapayClient($apiKey, $secretKey);

$createdPayment = $pallapayClient->payment()->create(
    'AED',
    '100',
    'johndoe@gmail.com',
    'https://yourwebsite.com/success',
    'https://yourwebsite.com/failed',
    'https://yourwebsite.com/webhook', // Optional
    'John', // Optional
    'Doe', // Optional
    'My Custom Note', // Optional
    'Order ID' // Optional
    //'USDT', // (paymentCurrencySymbol => Force the user to pay only in the selected currency, for example: USDT, ETH, ...) Optional
);

echo $createdPayment["data"]["payment_link"];
```

`create` method params:

| Name                   | Description                                                                                                                                                 | Required |
|------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------|----------|
| $symbol                | Currency of the payment                                                                                                                                     | YES      |
| $amount                | Amount in selected currency                                                                                                                                 | YES      |
| $payerEmailAddress     | Payer email address                                                                                                                                         | YES      |
| $ipnSuccessUrl         | The URL that we redirect the user after successful payment                                                                                                  | YES      |
| $ipnFailedUrl          | The URL that we redirect the user after unsuccessful payment                                                                                                | YES      |
| $webhookUrl            | Webhook URL (If webhookUrl is NULL pallapay will send notifications to default webhook URL that you entered while creating your API Key)                    | NO       |
| $payerFirstName        | Payer first name                                                                                                                                            | NO       |
| $payerLastName         | Payer last name                                                                                                                                             | NO       |
| $note                  | You can pass any custom note here. for example, your customer ID. This item is not displayed to the buyer (You will receive this in your webhook URL too)   | NO       |
| $orderId               | You can pass a "**unique**" order id here. This item is not displayed to the buyer as well (You will receive this in your webhook URL too)                  | NO       |
| $paymentCurrencySymbol | Force the user to pay only in the selected cryptocurrency, for example: USDT, ETH, ... (If you dont provide anything user can pay using any cryptocurrency) | NO       |


After that you can redirect user to `payment_link`.

#### Handle IPN notifications

After user payment was done, we will call your WEBHOOK_URL that you entered when you created your API Key.

In that page you can use this `getIpnData` method to get payment details and then verify it.

```php
use Pallapay\PallapaySDK\PallapayClient;

$apiKey = "YOUR_API_KEY";
$secretKey = "YOUR_SECRET_KEY";
$pallapayClient = new PallapayClient($apiKey, $secretKey);

$jsonData = file_get_contents('php://input');
$data = json_decode($inputJson, TRUE);

$ipnData = $pallapayClient->payment()->getIpnData($data);

if ($ipnData->isValid() && $ipnData->isPaid()) {
    print_r($ipnData->getAll())
    echo 'Paid Successfully';
} else {
    echo 'Not Paid';
}
```

`IpnData` Available methods:

| method                | Description                                                                                  |
|-----------------------|----------------------------------------------------------------------------------------------|
| isValid               | Check if IPN request was valid (Was really sent from Pallapay)                               |
| isPaid                | Check if user payment status was PAID                                                        |
| getAll                | Get everything from IPN request in an array                                                  |
| getPaymentRequestId   | Unique ID of created payment                                                                 |
| getPaymentAmount      | Payment amount in selected currency                                                          |
| getPaymentCurrency    | Selected fiat currency to pay                                                                |
| getPayerEmailAddress  | Payer email address                                                                          |
| getStatus             | Payment status (`PAID` or `UNPAID`)                                                          |
| getReceivingAmount    | The amount that you will receive in your Pallapay balance (After fees, if applicable)        |
| getReceivingCurrency  | The currency that you will receive in your Pallapay balance                                  |
| getPaidCryptocurrency | The cryptocurrency your user selected to pay with                                            |
| getFeeAmount          | Payment fee in selected fiat currency                                                        |
| getFeePaidBy          | Who paid the fees on this payment (You can choose who pay for fees in dashboard -> API Keys) |
| getPayerFirstName     | Payer first name                                                                             |
| getPayerLastName      | Payer last name                                                                              |
| getRefId              | User payment reference ID                                                                    |
| getPaidAt             | Payment was done at (date/time)                                                              |
| getNote               | Custom note that you pass in creation time                                                   |
| getOrderId            | Unique order ID that you pass in creation time                                               |

#### Available methods

- `$pallapayClient->payment()->create()`
- `$pallapayClient->payment()->getAll()`
- `$pallapayClient->payment()->getByPaymentRequestId()`
- `$pallapayClient->balance()->getAll()`
- `$pallapayClient->balance()->getBySymbol()`

#### Contribution

Contributions are highly appreciated either in the form of pull requests for new features, bug fixes or just bug reports.

----------------------------------------------

[Pallapay Website](https://www.pallapay.com)

