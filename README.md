# Zenziva SMS Client

[Zenziva](https://zenziva.net) SMS client. 

## Installation

Run [composer](http://getcomposer.org)

```bash
composer require nasution/zenziva-sms
```

## Usage

Make sure you already have Zenziva account. 

```php
require 'vendor/autoload.php';

use Nasution\ZenzivaSms\Client as Sms;

$sms = new Sms('userkey', 'passkey');


$sms->send('08123456789', 'Halo apa kabar?');

// Another method

$sms->to('08123456789')
    ->text('Halo apa kabar?')
    ->send();
```

## License

MIT © [Mulia Arifandi Nasution](http://mul14.net)
