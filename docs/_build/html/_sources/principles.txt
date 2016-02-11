
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


