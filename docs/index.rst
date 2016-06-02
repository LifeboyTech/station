`Repository on Github <https://github.com/LifeboyTech/station>`_

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
* Define multiple user ``groups`` where users can only access areas configured for their own group. 
* A bootstrap-based UI containing necessary behaviors such as drag and drop reorderables, image upload w/ crop tools, nested sortables, and more.
* User management functions such as password reminders and password reset.

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
   panel-options
   element-options
   models
   emailers
   license
   roadmap


Contributing to Station Development
----------------------------------- 

I welcome and encourage contributions. Please submit pull-requests. I will review and consider implementing.

My only guideline is that enhancements to Station should be within the simple and straightforward spirit of the project.

To build documentation, navigate to ``/docs`` directory and run ``touch *.rst; make html;``. `Read more <http://www.sphinx-doc.org/en/stable/>`_


Issues?
------- 

Please use the `Github issue tracker <https://github.com/LifeboyTech/station/issues>`_ to report problems or to get answers to your questions.


Credits
------- 

Station was conceived and built by `Ben Hirsch <https://github.com/phirschybar>`_. The project was named by `Tim Habersack <https://github.com/timbotron>`_ who also contributed to feature development and strategy in its early phases.

