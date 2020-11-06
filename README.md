# Zenziva Client for PHP - v2

This is v2, for previous version please check [1.x branch](https://github.com/mul14/zenziva.php/tree/1.x).

[Zenziva](https://www.zenziva.id) provide services to send SMS, WhatsApp, and voice message.

If this library not working for you, or you found any kind of bugs, please create a new issue.

## Install

Run [composer](http://getcomposer.org)

```bash
composer require nasution/zenziva
```

## Usage

Make sure you already have Zenziva account.

```php
// Regular

require 'vendor/autoload.php';

use Nasution\Zenziva\Zenziva;

$zenziva = new Zenziva('userkey', 'passkey');

// SMS
$zenziva->sms('0812223333', 'Halo');

// WhatsApp
$zenziva->wa('6285551111', 'Halo');

// Voice message
$zenziva->voice('0812223333', 'Halo');
```

```php
// SMS Masking
$zenziva = new Zenziva('userkey', 'passkey', [
    'masking' => true,
]);

$zenziva->sms('0812223333', 'Halo');
```

```php
// Zenziva Sms Center
$zenziva = new Zenziva('userkey', 'passkey', [
    'domain' => 'domain_name.com',
]);

$zenziva->sms('0812223333', 'Halo');
```

```php
// Zenziva WhatsApp Center
$zenziva = new Zenziva('userkey', 'passkey', [
    'domain' => 'domain_name.com',
    'whatsapp_id' => 'whatsapp_id',
]);

$zenziva->wa('6285551111', 'Halo');
```
