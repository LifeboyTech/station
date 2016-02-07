.. Station documentation master file, created by
   sphinx-quickstart on Sun Feb  7 04:28:10 2016.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Station for Laravel 5
=====================

Contents:

.. toctree::
   :maxdepth: 1

   start


About Station 
------------- 

Station allows developers to auto-generate, configure, and deploy an admin interface with advanced scaffolding, migrations and models for their Laravel software. It includes an artisan build command and a simple but extensible UI.

If you are tired of creating `CRUD <https://en.wikipedia.org/wiki/Create,_read,_update_and_delete>`_-capable interfaces from scratch, then Station may be perfect for you. In a few minutes you can have a back-end admin installed and ready for use by you, your customers, and your customers' users.

Station shines when used as a CMS however it is *not* your typical template-based CMS system. It does not include front-end templates or layouts. Instead it is intended to be used as a database, content, and user management system. It takes away the heavy-lifting involved in creating a back-end for your web site or application. But, it leaves the front-end a blank canvas for your creativity!

Features 
-------- 

* A password protected section of your domain under ``/station/...`` or the virtual directory name of your choice
* Authenticated user system utilizes Laravel's native user model 
* A simple set of configuration text files defines your entire database, permissions and navigation schema
* A build command at ``php artisan station:build`` will generate + run migrations and create models
* Define multiple user ``groups`` where users can only access areas for their own group 
* A bootstrap-based UI containing necessary behaviors such as drag and drop reorderables, image upload w/ crop tools, nested sortables, and more

Requirements
------------ 

* All of the basic requirements of the Laravel library
* GD (compiled with PHP, if you want to take advantage of the image resizing features)

Todos
----- 

* Replace `Session::*`
* Replace `Request::*`
* Replace `->lists()` - convert to array - this now returns an object
