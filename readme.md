# Station

## Incredibly Flexible CRUD, Content and User Management System For Laravel 4.1

Tired of creating and configuring similar models and controllers which deal with basic CRUD, validation and user role-based capabilities? Station allows developers to setup and configure a backend CMS for a Laravel app/site very quickly. 

## Features

* UI using a vanilla bootstrap layout which you can style in your own app.
* Allows for easy table association setup through config files.

## Requirements

* GD (compiled with PHP, if you want to take advantage of the image resizing features)

## Installation 

The Station Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the
`canary/station` package in your project's `composer.json`.

```json
{
    "require": {
        "canary/station": "0.1.*"
    }
}
```

Then run a composer update
```sh
composer update
```

## Configuration & Setup

This assumes you have a working dev or production environment with Laravel 4 already installed.

### 1. Register Station in app/config/app.php

To use station, you must register the provider when bootstrapping your Laravel application.

Find the `providers` key in your `app/config/app.php` and register the Station Service Provider.

```php
    'providers' => array(
        // ...
        'Canary\Station\StationServiceProvider',
        'Way\Generators\GeneratorsServiceProvider',
    )
```

### 2. Use artisan to set up Station's default config files within your app

Then publish the package configuration files using Artisan. This will copy Station's default configuration to your app. You can then change and add to these configuration files as needed.

```sh
php artisan config:publish canary/station 
```

At this time you can (optionally) edit `/app/config/packages/canary/station/_app.php` and change the `root_admin_email`

### 3. Run Station's Build Command. 

This will generate migrations, run migrations, generate models, and seed the database.

```sh
php artisan station.build 
```

### 4. Publish The Package's Assets To Your App

```sh
php artisan asset:publish canary/station 
```

### 5. Test Installation

You should now be able to browse to your app at:

http://{host}/station/ (ex. http://app.localhost/station/) and see station running without errors.

You can log in using user/password: `admin/admin`

### 6. Configure Station and Your Panels!

Start by editing `/app/config/packages/canary/station/_app.php`

Then create files for each panel in /app/config/packages/canary/station/ [we need documentation on this]

That's it. You now have a fully functioning back end and user management system for your site.

## Notable Limitations

* The validation rule 'unique' MUST accept 2 parameters: table name, and column name. No more and no less. [ex. unique:users,username]
