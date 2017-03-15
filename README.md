# Logs for Laravel 5

## Install

Via Composer

``` bash
$ composer require webmachine/logs
```

Next, you must install the service provider and facade alias:

```php
// config/app.php
'providers' => [
    ...
    Webmachine\Logs\LogsServiceProvider::class,
];

...

'aliases' => [
    ...
    'Logs' => Webmachine\Logs\LogsFacade::class,
];
```

Publish

``` bash
$ php artisan vendor:publish --provider="Webmachine\Logs\LogsServiceProvider"
```

## Usage

Add doer trait to user model
``` php
...
use Webmachine\Logs\Traits\LogDoer;

class User extends Model {
    use LogDoer;
    ...
}
```

Add traits to target models
``` php
...
use Webmachine\Logs\Traits\LogTarget;

class Foo extends Model {
    use LogTarget;
    ...
}
```

You can add a custom log description:
``` php
...
use Webmachine\Logs\LogsFacade as Logs;
...
public function storage() {
    ...
    Logs::setDescription('custom description');
    \App\Foo::create([...]);
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
