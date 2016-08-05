# Zenziva SMS Client

[Zenziva](https://zenziva.net) SMS client. 

## Installation

Run [composer](http://getcomposer.org)

```bash
composer require nasution/zenziva-sms
```

## Usage

### Standalone usage

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

### Use with Laravel 5.3 Notification

You need to register the service provider. Open `config/app.php`, add this line inside `providers`.

```php
Nasution\ZenzivaSms\NotificationServiceProvider::class,
```

Insert this inside your `config/services.php`, 

```php
'zenziva' => [
    'userkey' => 'your-userkey',
    'passkey' => 'your-password',
],
```

Add this line to your `User` model (or any notifiable model),
  
```php
public function routeNotificationForZenzivaSms()
{
    return $this->phone_number; // Depends on your users table field.
}
```

On your Notification class, add this inside via method. Like so

```php

use Nasution\ZenzivaSms\NotificationChannel as ZenzivaSms;

// ...

public function via($notifiable)
{
    return [ZenzivaSms::class];
}
```

Now, we are ready to use notification feature in Laravel 5.3

```
use App\User;

Route::get('/', function () {
    // Send notification to all users
    $users = User::all();
    \Notification::send($users, new PingNotification);
    
    // Or just for one user
    User::find(1)->notify(new PingNotification);
});
```

## License

MIT © [Mulia Arifandi Nasution](http://mul14.net)
