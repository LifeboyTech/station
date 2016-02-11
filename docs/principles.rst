
Basic Principles & Configuration 
================================

Station has two levels of configuration: **Panel Level** and **Application Level**.


.. _panel-level-configuration:

Panel Level Configuration 
------------------------- 

Each individual section that appears in the navigation bar is referred to as a "panel". By default, a panel contains all of the functionality to create, remove, update and delete records from a database table or foreign table. Panels can be customized in numerous ways. Panels can also be overriden entirely so that you may develop your own functionality and circumvent default panel behaviors.

To learn all of the specifics of configuring panels, refer to :ref:`panel-anatomy`.



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





