# Logs for Laravel 5

## Install

Via Composer

``` bash
$ composer require webmachineltda/logs
```

Next, you must install the service provider and facade alias:

```php
// config/app.php
'providers' => [
    ...
    Webmachineltda\Logs\LogsServiceProvider::class,
];

...

'aliases' => [
    ...
    'Logs' => Webmachineltda\Logs\LogsFacade::class,
];
```

Publish

``` bash
$ php artisan vendor:publish --provider="Webmachineltda\Logs\LogsServiceProvider"
```

## Usage

Add doer trait to user model
``` php
...
use Webmachineltda\Logs\Traits\LogDoer;

class User extends Model {
    use LogDoer;
    ...
}
```

Add traits to target models
``` php
...
use Webmachineltda\Logs\Traits\LogTarget;

class Foo extends Model {
    use LogTarget;
    ...
}
```

You can add a custom log description:
``` php
...
use Webmachineltda\Logs\LogsFacade as Logs;
...
public function storage() {
    ...
    Logs::setDescription('custom description');
    \App\Foo::create([...]);
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
