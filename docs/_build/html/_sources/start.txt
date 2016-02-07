
Getting Started 
===============

Installation 
------------

Station should be installed via `Composer <http://getcomposer.org>`_ by requiring the
``lifeboy/station`` package in your project's ``composer.json``.

.. code-block:: json

	{
	    "require": {
	        "lifeboy/station": "dev-master"
	    }
	}

Then run a composer update

.. code-block:: sh

	composer update


Configuration & Setup
--------------------- 

This assumes you have a working dev or production environment with Laravel 5 and a database already installed and configured.

**1. Register Station in app/config/app.php**

To use station, you must register the provider when bootstrapping your Laravel application.

Find the ``providers`` key in your ``app/config/app.php`` and register the Station Service Provider.

.. code-block:: php 

    'providers' => array(
        // ... add below ...
        Lifeboy\Station\StationServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
    ),

Also update the ``aliases`` array 

.. code-block:: php 

    'aliases' => [
        // ...
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
    ],

In ``app/Http/Kernel.php``, add:

.. code-block:: php 

	protected $routeMiddleware = [
	    // ...
	    'station.session' => \Lifeboy\Station\Filters\Session::class
	];

**2. Publish Station's assets over to your app.**

.. code-block:: sh 

	php artisan vendor:publish

At this time you can (optionally) edit ``/app/config/packages/lifeboy/station/_app.php`` and change the ``root_admin_email``

**3. Run default migrations if you haven't already.**

.. code-block:: sh 
	
	php artisan migrate

**4. Run Station's Build Command**

This will generate migrations, run migrations, generate models, and seed the database.

.. code-block:: sh 

	php artisan station:build 


**5. Test Installation**

You should now be able to browse to your app at:

http://{host}/station/ (ex. http://app.localhost/station/) and see station running without errors.

You can log in using user/password: ``admin/admin``


**6. Configure Station and Your Panels!**

Start by editing ``/app/config/packages/lifeboy/station/_app.php``

Then create files for each panel in /app/config/packages/lifeboy/station/ [we need documentation on this]

That's it. You now have a fully functioning back end and user management system for your site.


