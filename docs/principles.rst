
Basic Principles & Configuration 
================================

Station has three levels of configuration: **Panel Level**, **Field Level** and **Application Level**.


.. _panel-level-configuration:

Panel Level Configuration 
------------------------- 

Each individual section that appears in the navigation bar is referred to as a "panel". By default, a panel contains all of the functionality to create, remove, update and delete records from a database table or foreign table. Panels can be customized in numerous ways. Panels can also be overriden entirely so that you may develop your own functionality and circumvent default panel behaviors.

To learn all of the specifics of configuring panels, refer to :ref:`panel-anatomy`.



.. _field-level-configuration:

Field Level Configuration 
------------------------- 

Each panel can have any number of fields associated with it. Typically each field refers to a specific field in your database but that is not always the case. A panel can have "virtual" fields as well as no fields at all.

To learn all of the specifics of configuring panel fields, refer to :ref:`panel-anatomy`.



.. _app-level-configuration:

Application Level Configuration
-------------------------------

All of Station's top level configuration occurs in the ``config/packages/lifeboy/station/_app.php`` file. The boilerplate version of this file was copied to your app during the installation process.

Following is a list of all of the available configuration options:


**name**

	This is the name of your site or application 

**root_admin_email**

	This value will be seeded into the ``users`` table when the admin user is created during the installation process. 

**root_uri_segment**

	This value is what Station will use as its pseudo-directory "slug". By default this is set to ``station`` which means that all of the URLs for Station will be found at ``http://my-site.com/station/...``. You could use ``admin`` which would result in all URLs looking like ``http://my-site.com/admin/...``

**panel_for_user_create**

	This is the name of the panel which is used for creating new users. You may never need to use this as your app will likely have it's own onboarding process. However this panel is shipped with Station in case you want a simple way for new users to create an account.

**user_group_upon_register**

	This is the group which a new user will be automatically set to upon creation.

**css_override_file**

	Optionally set this to the URL or path of a .css file which will be loaded on every Station URL. This is excellent for adding branding specifics to your Station installation.

**media_options**

	**AWS:** Station currently only supports file uploads to AWS/S3. Enter your bucket, key and secret key here to establish connectivity. If your app does not require uploads then you do not need to configure this option.

	**Sizes:** This is where you set the default image sizes for all uploaded images. If any of your panel fields utilize image uploads and *do not* specify a set of image sizes, these sizes will be used. This is a handy way to set standard sizes for all of your images site-wide, if needed. For more information how to configure this option, refer to the :ref:`config-images` configuration documentation.

**user_groups**

	This associative array defines the user groups for your app but also, just as importantly, defines the **entire navigation structure** of Station. Please refer to the boilerplate sample of ``config/packages/lifeboy/station/_app.php``, included in the installation, to visualize the structure of this document.

	**user_groups.[group name]:** This associative array's key is the group name. This is only for internal use. You can use any name you want. When you run ``php artisan station:build`` your groups will be seeded to the ``groups`` table in your database and presented as options in your ``users`` panel.

	**user_groups.[group name].starting_panel:** This is the panel key that a user belonging to this group will be redirected to upon log in. The syntax for this is ``panel_name.ACTION``, where action is L (list view), C (create view), or U (update view).

	**user_groups.[group name].panels** This nested associative array defines the navigation that a user from this group will see in their navigation bar. It also defines the sections, panel titles, and permissions that a user of this group has regarding these panels. The format is as follows:

	.. code-block:: php 

		'panels' => [

			'demo_section'   => ['name' => 'Section Header',  'is_header' => TRUE, 'icon' => 'glyphicon glyphicon-book'],
			'posts'          => ['name' => 'Posts',           'permissions' => 'CRUDL'],

			... more sections headers and panels go here ...
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




