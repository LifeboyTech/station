
.. _panel-anatomy:

Working With Panels  
===================

Panels are the heart and soul of Station. Out of the box, panels contains all of the functionality necessary for a user to manipulate a specific type of data in your database. There are numerous options for panels and elements giving you the flexibility to craft back-end tools to fit virtually any scenario or database structure.

Typically each panel maps to a database table one-to-one. However, a panel can also contain one or more **subpanels** as well as references to other tables for lookup capabilities.

A panel can also be complete overriden so you can build your own functionality from scratch, but within the user-authenticated comfort of Station.

.. _build-command:

The Build Command
-----------------

Each time you add a new panel or change a panel's configuration you need to run ``php artisan station:build``.

This artisan command will analyze your panels and elements and then do the following:

* generate migrations for any missing database fields, including database pivot tables
* run the new migrations 
* generate / refresh models with foreign relationships for each panel (see ":ref:`models`" for more info)
* seed the database (only when new user groups have been added)

If you attempt to navigate to a newly created panel in the Station navigation, you will likely encounter errors. You must run the build command first.


.. _accessing-panel-configuration:

Accessing Panel Configuration
----------------------------- 

In your Laravel app you may wish to access a panel's configuration array. To accomplish this, just add ``use Lifeboy\Station\Config\StationConfig as StationConfig`` to the top of any class. Then,

.. code-block:: php 

   $my_panel_config = StationConfig::panel('my_panel_name');


Panel Creation Workflow
----------------------- 

As discussed in ":ref:`app-level-configuration`", when you add new array elements to the ``user_groups`` associative array in your ``config/packages/lifeboy/station/_app.php`` file, you are in-effect registering new panels with Station.

Station is able to determine the behaviors of each panel given the values of the configuration parameters found in each panel's configuration file. For example, if you have a panel named ``posts`` in your ``config/packages/lifeboy/station/_app.php`` file, then you need a corresponding ``posts.php`` file in the ``config/packages/lifeboy/station/`` directory to define the parameters of that panel.

For example, refer to the ``config/packages/lifeboy/station/users.php`` file which was packaged with your installed copy of Station, you will notice that there are two top-level array keys, ``panel_options`` and ``elements``:

.. code-block:: php 

	'panel_options'	=> [
		
		// define your panel-level options here...	
	],
			
	'elements'	=> [

		// define your element-level options here...
	],

Panel options control the overall configuration of the panel (think of this as database table-level options). Elements define how your users interact with specific database fields.

Without further ado, what follows is an exhaustive list of each and every panel and element option possible along with specific examples for each. Dive in!

