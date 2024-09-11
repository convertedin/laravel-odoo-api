# Laravel Odoo Api

This is a medium level API to Odoo (former OpenERP) XMLRPC-API for Laravel. [Odoo website](https://www.odoo.com)

This package is a successor of [Laradoo](https://github.com/Edujugon/laradoo), but there is no backwards compatibility!

:warning: **This Package is not Maintained any more. Successor is [Odoo Jsonrpc](https://packagist.org/packages/Convertedin/odoo-jsonrpc)**



## Compatibility

Laravel 7 and higher

Odoo 8.0 and higher

Php 7.4 and higher

## Installation

This package is installed via [Composer](https://getcomposer.org/). To install, run the following command.

```shel
composer require Convertedin/laravel-odoo-api
```

Publish the package's configuration file to the application's own config directory

```php
php artisan vendor:publish --provider="Convertedin\LaravelOdooApi\Providers\OdooServiceProvider" --tag="config"
```

### 

This package supports autodiscover.

If you don't use autodiscover for reasons, you can add the provider as described below.

Register Laravel Odoo Api service by adding it to the providers array.

```php
'providers' => array(
        ...
        Convertedin\LaravelOdooApi\Providers\OdooServiceProvider::class
    )
```

You can also add the Alias facade.
```php
'aliases' => array(
        ...
        'Odoo' => Convertedin\LaravelOdooApi\Facades\Odoo::class,
    )
```

### Configuration

After publishing the package config file, the base configuration for laravel-odoo-api package is located in config/laravel-odoo-api.php


Also, you can dynamically update those values calling the available setter methods:

`host($url)`, `username($username)`, `password($password)`, `database($name)`, `apiSuffix($name)`


##  Usage samples

Instance the main Odoo class:

```php
$odoo = new \Convertedin\LaravelOdooApi\Odoo();
```
You can get the Odoo API version just calling the version method:

```php
$version = $odoo->version();
```
> This methods doesn't require to be connected/Logged into the ERP.

Connect and log into the ERP:

```php
$odoo = $odoo->connect();
```

All needed configuration data is taken from `laravel-odoo-api.php` config file. But you always may pass new values on the fly if required.

```php
$this->odoo = $this->odoo
            ->username('my-user-name')
            ->password('my-password')
            ->database('my-db')
            ->host('https://my-host.com')
            ->connect();
```
> // Note: `host` should contain 'http://' or 'https://'

After login, you can check the user identifier like follows:

```php
$userId = $this->odoo->getUid();
```

You always can check the permission on a specific model:

```php
$can = $odoo->can('read', 'res.partner');
```
> Permissions which can be checked: 'read','write','create','unlink'

Method `search provides a collection of ids based on your conditions:

```php
$ids = $odoo
    ->model('res.partner')
    ->where('customer', '=', true)
    ->search();
```

You can limit the amount of data using `limit` method and use as many as condition you need:

```php
$ids = $odoo
    ->model('res.partner')
    ->where('is_company', true)
    ->where('customer', '=', true)
    ->limit(3)
    ->search();
```

If need to get a list of models, use the `get` method:

```php
$models = $odoo
    ->model('res.partner')
    ->where('customer', true)
    ->limit(3)
    ->get();
```

Instead of retrieving all properties of the models, you can reduce it by adding `fields` method before the method `get`

```php
$models = $odoo
    ->model('res.partner')
    ->where('customer', true)
    ->limit(3)
    ->fields(['name'])
    ->get();
```

If not sure about what fields a model has, you can retrieve the model structure data by calling `fieldsOf` method:

```php
$structure = $odoo
    ->model('res.partner')
    ->listModelFields();
```

Till now we have only retrieved data from Odoo but you can also Create and Delete records.

In order to create a new record just call `create` method as follows:

```php
$id = $odoo
    ->model('res.partner')
    ->create(['name' => 'Bobby Brown']);
```
> The method returns the id of the new record.

For Deleting records we have the `delete` method:

```php
$result = $odoo
    ->model('res.partner')
    ->where('name', '=', 'Bobby Brown')
    ->delete();
```
> Notice that before calling `delete` method you have to use `where`.

You can also remove records by ids like follows:

```php
$result = $odoo
    ->model('res.partner')
    ->deleteById($ids);
```

Update any record of your Odoo:

```php
$updateSuccessfull = $odoo
    ->model('res.partner')
    ->where('name', '=', 'Bobby Brown')
    ->update(['name' => 'Dagobert Duck','email' => 'daduck@odoo.com']);
```

Notice that all `delete` and `update` methods always returns `true` except if there was an error.

Custom api Calls are also supported

```php
$ids = $odoo
    ->model('res.partner')
    ->setMethod('search')
    ->setArguments([[
        ['is_company', '=', true]
    ]])
    ->setOption('limit', 3)
    ->addResponseClass(Odoo\Response\ListResponse::class)
    ->get();

```
