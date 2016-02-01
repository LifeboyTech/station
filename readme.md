## Laravel 5: Backend UI for Content & User Management

**Note: Looking for a Laravel 4 version? Use this: https://github.com/thecanarycollective/station**

**Documentation is coming to the Github wiki very soon**

Tired of creating and configuring similar models and controllers which deal with basic CRUD, validation and user role-based capabilities? Station allows developers to setup and configure a backend CMS for a Laravel app/site very quickly. 

## Features

* UI using a vanilla bootstrap layout which you can style in your own app.
* Allows for easy table association setup through config files.

## Requirements

* GD (compiled with PHP, if you want to take advantage of the image resizing features)

## Installation 

The Station Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the
`lifeboy/station` package in your project's `composer.json`.

```json
{
    "require": {
        "lifeboy/station": "dev-master"
    }
}
```

Then run a composer update
```sh
composer update
```

## Configuration & Setup

This assumes you have a working dev or production environment with Laravel 5 and a database already installed and configured.

### 1. Register Station in app/config/app.php

To use station, you must register the provider when bootstrapping your Laravel application.

Find the `providers` key in your `app/config/app.php` and register the Station Service Provider.

```php
    'providers' => array(
        // ... add below ...
        Lifeboy\Station\StationServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
    ),
```

Also update the `aliases` array 

```php 
    'aliases' => [
        // ...
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
    ],
```

In `app/Http/Kernel.php`, add:

```php 
protected $routeMiddleware = [
    // ...
    'station.session' => \Lifeboy\Station\Filters\Session::class
];
```

### 2. Publish Station's assets over to your app.

```sh
php artisan vendor:publish
```

At this time you can (optionally) edit `/app/config/packages/lifeboy/station/_app.php` and change the `root_admin_email`

### 3. Run default migrations if you haven't already. 

`php artisan migrate` 

### 4. Run Station's Build Command. 

This will generate migrations, run migrations, generate models, and seed the database.

```sh
php artisan station:build 
```

### 5. Test Installation

You should now be able to browse to your app at:

http://{host}/station/ (ex. http://app.localhost/station/) and see station running without errors.

You can log in using user/password: `admin/admin`

### 6. Configure Station and Your Panels!

Start by editing `/app/config/packages/lifeboy/station/_app.php`

Then create files for each panel in /app/config/packages/lifeboy/station/ [we need documentation on this]

That's it. You now have a fully functioning back end and user management system for your site.

## Notable Limitations

* The validation rule 'unique' MUST accept 2 parameters: table name, and column name. No more and no less. [ex. unique:users,username]


## TODO 

* Replace `Session::*`
* Replace `Request::*`
* Replace `->lists()` - convert to array - this now returns an object