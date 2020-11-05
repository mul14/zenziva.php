# Zenziva Client for PHP - v2

This is v2, for v1 please check 1.x branch.

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
require 'vendor/autoload.php';

use Nasution\Zenziva\Zenziva;

$zenziva = new Zenziva('userkey', 'passkey');

// SMS
$zenziva->sms('08123456789', 'Halo');

// WhatsApp
$zenziva->wa('08123456789', 'Halo');

// Voice message
$zenziva->voice('08123456789', 'Halo');
```

```php
// Masking
$zenziva = new Zenziva('userkey', 'passkey', [
    'masking' => true,
]);
```

```php
// {Sms,WhatsApp} Center
$zenziva = new Zenziva('userkey', 'passkey', [
    'domain' => 'domain_name.com',
]);
```
