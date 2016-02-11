.. Station documentation master file, created by
   sphinx-quickstart on Sun Feb  7 04:28:10 2016.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Station for Laravel 5
=====================

Station helps Laravel developers to auto-generate, configure, and deploy an admin interface with advanced scaffolding, migrations and models for their Laravel software. It includes an artisan build command and a simple but extensible UI.

If you are tired of creating `CRUD`_-capable interfaces from scratch, Station may be perfect for you. In a few minutes you can have a simple, but advanced back-end admin installed and ready for use by you, your customers, and your customers' users.

Station shines when used as a CMS however it is *not* your typical template-based CMS system. It does not include front-end templates or layouts. Instead it is intended to be used as a database, content, and user management system. It takes away the heavy-lifting involved in creating a back-end for your web site or application. But, it leaves the front-end a blank canvas for your creativity!

.. _CRUD: https://en.wikipedia.org/wiki/Create,_read,_update_and_delete

Features 
-------- 

* A password-protected section of your domain under ``/station/...`` or the virtual directory name of your choice.
* Authenticated user system utilizes Laravel's native `user model`_.
* A simple set of configuration text files defines your entire database, permissions and navigation schema.
* A build command at ``php artisan station:build`` will generate + run migrations and create models.
* Define multiple user ``groups`` where users can only access areas for their own group. 
* A bootstrap-based UI containing necessary behaviors such as drag and drop reorderables, image upload w/ crop tools, nested sortables, and more.

.. _user model: https://laravel.com/docs/5.2/authentication

Requirements
------------ 

* All of the `basic requirements of the Laravel library <https://laravel.com/docs/5.2#server-requirements>`_
* GD (compiled with PHP, if you want to take advantage of the image resizing features)


Documentation Contents
----------------------

.. toctree::
   :maxdepth: 1

   start
   principles
   panels
   license
   roadmap


Contributing to Station Development
----------------------------------- 

I welcome and encourage contributions. Please submit pull-requests. I will review and consider implementing.

My only guideline is that enhancements to Station should be within the simple and straightforward spirit of the project.

