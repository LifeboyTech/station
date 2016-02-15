
Basic Principles & Configuration 
================================

Station has three levels of configuration: **Panel Level**, **Element Level** and **Application Level**.


.. _panel-level-configuration:

Panel Level Configuration 
------------------------- 

Each individual section that appears in the navigation bar is referred to as a "panel". By default, a panel contains all of the functionality to create, remove, update and delete records from a database table or foreign table. Panels can be customized in numerous ways. Panels can also be overriden entirely so that you may develop your own functionality and circumvent default panel behaviors.

To learn all of the specifics of configuring panels, refer to :ref:`panel-anatomy`.



.. _element-level-configuration:

Element Level Configuration 
---------------------------

Each panel can have any number of elements associated with it. Typically each element refers to a specific element in your database but that is not always the case. A panel can have "virtual" elements as well as no elements at all.

To learn all of the specifics of configuring panel elements, refer to :ref:`panel-anatomy`.



.. _app-level-configuration:

Application Level Configuration
-------------------------------

All of Station's top level configuration occurs in the ``config/packages/lifeboy/station/_app.php`` file. The boilerplate version of this file was copied to your app during the installation process.

Following is a list of all of the available configuration options:


name
^^^^

	This is the name of your site or application 

root_admin_email
^^^^^^^^^^^^^^^^

	This value will be seeded into the ``users`` table when the admin user is created during the installation process. 

root_uri_segment
^^^^^^^^^^^^^^^^

	This value is what Station will use as its pseudo-directory "slug". By default this is set to ``station`` which means that all of the URLs for Station will be found at ``http://my-site.com/station/...``. You could use ``admin`` which would result in all URLs looking like ``http://my-site.com/admin/...``

panel_for_user_create
^^^^^^^^^^^^^^^^^^^^^

	This is the name of the panel which is used for creating new users. You may never need to use this as your app will likely have it's own onboarding process. However this panel is shipped with Station in case you want a simple way for new users to create an account.

user_group_upon_register
^^^^^^^^^^^^^^^^^^^^^^^^

	This is the group which a new user will be automatically set to upon creation.

css_override_file
^^^^^^^^^^^^^^^^^

	Optionally set this to the URL or path of a .css file which will be loaded on every Station URL. This is excellent for adding branding specifics to your Station installation.

.. _media-options:

media_options
^^^^^^^^^^^^^

	**AWS:** Station currently only supports file uploads to AWS/S3. Enter your bucket, key and secret key here to establish connectivity. If your app does not require uploads then you do not need to configure this option.

	**Sizes:** This is where you set the default image sizes for all uploaded images. If any of your panel elements utilize image uploads and *do not* specify a set of image sizes, these sizes will be used. This is a handy way to set standard sizes for all of your images site-wide, if needed. For more information how to configure this option, refer to the :ref:`config-images` configuration documentation.

user_groups
^^^^^^^^^^^

	This associative array defines the user groups for your app but also, just as importantly, defines the **entire navigation structure** of Station. Please refer to the boilerplate sample of ``config/packages/lifeboy/station/_app.php``, included in the installation, to visualize the structure of this document.

	**user_groups.[group name]:** This associative array's key is the group name. This is only for internal use. You can use any name you want. When you run ``php artisan station:build`` your groups will be seeded to the ``groups`` table in your database and presented as options in your ``users`` panel.

	**user_groups.[group name].starting_panel:** This is the panel key that a user belonging to this group will be redirected to upon log in. The syntax for this is ``panel_name.ACTION``, where action is L (list view), C (create view), or U (update view).

	**user_groups.[group name].panels** This nested associative array defines the navigation that a user from this group will see in their navigation bar. It also defines the sections, panel titles, and permissions that a user of this group has regarding these panels. The format is as follows:

	.. code-block:: php 

		<?php 
   
		'panels' => [

			'demo_section'   => ['name' => 'Section Header',  'is_header' => TRUE, 'icon' => 'glyphicon glyphicon-book'],
			'posts'          => ['name' => 'Posts',           'permissions' => 'CRUDL'],

			// ... more sections headers and panels go here ...
		]

	In the above example, **demo_section** is the key name for a section header. The actual name is irrelevant. Just make sure all of your section header keys have unique names because this is PHP array and you cannot duplicate your key names! **is_header** indicates that this item is only a header title and not an actual panel. The **icon** option allows you to use bootstrap glyphicon names to accompany your section headers.

	The **posts** key references an actual panel, not a section header. This key must match the name of a file in the ``config/packages/lifeboy/station`` directory where the :ref:`panel-anatomy` is defined. The **name** option is the actual title of the panel as it will appear in the naviagtion. 

	The **permissions** option sets the permissions that a user from this group has on this panel. You can enter any combination of the letters C.R.U.D. and L:

	.. code-block:: php 

		C = Create 
		R = Read 
		U = Update 
		D = Delete 
		L = List 

	For example, if you only specify the letter ``L`` for permissions then the user will only be able to list the records in this panel. Specifying all of the letters gives the user full permissions on this panel. 

html_append_file
^^^^^^^^^^^^^^^^

	This option allows you to specify an HTML or PHP blade file to append to every Station view. This is ideal for analytics.

html_prepend_content_file
^^^^^^^^^^^^^^^^^^^^^^^^^

	Like ``html_append_file`` you can specify an HTML or PHP blade file to prepend to the content area of every panel in Station. This is ideal for onboarding progress timelines or system-wide, universal alerts.

strict_domains
^^^^^^^^^^^^^^

	This forces all requests within Station to return a 404 unless one of the domains specified in this array is the domain indicated in the request. 

.. _config-variables:

Configuration Variables
-----------------------

The ``%user_id%`` variable can be used in any value of the application or panel config files. The user's ID will be replaced. This allows you to create panels which display only user-specific data. See :ref:`panel-anatomy` for more examples of where and how this can be used. See below on how this configuration variable can be used in the application level configuration:


.. _custom-config-variables:

Custom Configuration Variables
------------------------------

You can create your own custom configuration variables ``custom_user_vars`` which are accessible in any panel configuration file and the application configuration file. You can also create ``custom_view_vars`` which are available in any Station views. Just add them to the top-level of your ``config/packages/lifeboy/station/_app.php`` file.

.. code-block:: php 
	
	<?php 
   
	'custom_user_vars' => [

		'user_company_ids' => '\CompanyRepository::id_list_for_user(%user_id%)',
		'user_store_ids' => '\StoreRepository::id_list_for_user(%user_id%)',
	],

	'custom_view_vars' => [

		'onboarding_progress_html' => '\UserRepository::onboarding_progress_html_for(%user_id%)',
	],

In this example we are utilizing a ``CompanyRepository`` class, which is part of our Laravel app. This class is returning a set of IDs based on the current user's ID. Those IDs are now stored in ``%user_company_ids%``, which can be used in any panel configuration file.

Similarly, with ``custom_view_vars`` we are creating the variable ``$onboarding_progress_html`` which is now accessible in any Station view. In this example we're generating a snippet of HTML which is being inserted into the file that we specified as our ``html_prepend_content_file``. That snippet of HTML contains information about onboarding specific to the user who is logged in.

You can create as many of these custom variables as you wish.


