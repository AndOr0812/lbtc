## Localbitcoins.com API for Laravel
---

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

```
composer require ndlovu28/lbtc
```

Register the application in *config/app.php* by appending the line bellow in prividers section
```
Ndlovu28\Lbtc\LbtcServiceProvider::class,
```

Optionally you can add the bellow aliases section to call Lbtc in a short form.
```
'Lbtc' => Ndlovu28\Lbtc\Lbtc::class,
``` 

Load the database with the command bellow
```
php artisan migrate
```

### Usage

---

In your controller or class add the line bellow if you added aliases
```
use Lbtc;
```
Or this if you did not add aliases
```
use Ndlovu28/Lbtc/Lbtc;
```

Initialize the class with your localbitcoins *key* and *secret*

```
$lbtc = new Lbtc();
$lbtc->config($key, $secret);
```

#### Check Balance
```
$balance = $lbtc->checkBalance();
```


