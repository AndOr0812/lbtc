## Localbitcoins.com API for Laravel

This package conects localbitcoins.com API. With this package you can:
 - Query Balance for your wallet
 - Get Bitcoin buyers for a specific regoin
 - Initiate a sell trade
 - Release bitcoin to a specific trade
 - Get chat message from the buyer 

### Requirements

---

 - PHP 7.1+
 - Composer

### Installation

---

To install this package run the commands bellow.

```php
composer require ndlovu28/lbtc
```

Register the application in *config/app.php* by appending the line bellow in prividers section
```php
Ndlovu28\Lbtc\LbtcServiceProvider::class,
```

Optionally you can add the bellow aliases section to call Lbtc in a short form.
```php
'Lbtc' => Ndlovu28\Lbtc\Lbtc::class,
``` 

Load the database with the command bellow
```php
php artisan migrate
```

### Usage

---

In your controller or class add the line bellow if you added aliases
```php
use Lbtc;
```
Or this if you did not add aliases
```php
use Ndlovu28\Lbtc\Lbtc;
```

Initialize the class with your localbitcoins *key* and *secret*
```php
$lbtc = new Lbtc();
$lbtc->config($key, $secret);
```

#### Check Balance
```php
$balance = $lbtc->checkBalance();
```

#### Get an andvert
Get advert with a matching amount to sell bitcoin for, the type of transaction, and location information. This will return the advert id if the found or false if not found
```php
$ad_id = $lbtc->getBuyers('20000', 'm-pesa-tanzania-vodacom', 'Tanzania', 'TZ');
```
#### Initialize trade
Start a trade with the given ad_id from get advert query. See [here](https://localbitcoins.com/api-docs/online-buy-fields/) for *trx_data* array for the required fields for diiiferent payment methods.
```php
$contact_id = $lbtc->initTrade($ad_id, $amount, $message, $trx_data);
```
The above will return *contact_id* which can be used to send messages to the buyer and check status of the trade.
```php
//Send mesaage to the buyer
$result = $lbtc->sendMessage($contact_id, $message);

//Get messages array from the buyer
$messages = $lbtc->getMessages($contact_id);

//Check the status of the trade, return true if complete of false if its still pending payment
$status = $lbtc-<checkTrade($contact_id);

//Release bitcoins once the payment has been marked as PAID: Retunrns boolean for status
$lbtc->releseTrade($contact_id)
```
