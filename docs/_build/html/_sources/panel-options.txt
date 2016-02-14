Panel Options
=============

The ``panel_options`` key of the array defined in any of the files at ``/config/packages/lifeboy/station`` represents the panel-level options which are available to users of Station. 

Generally, each panel is mapped to a specific database table. However, this is not always the case. Some panels have an :ref:`override` defined. See below for the full documentation on configuring panels.

.. note:: 
   
   All options marked with a * are required
   

.. _table:

table * 
-------

Quite simply, this is the name of the database table to which this panel corresponds. Note that you can have many panels which use the same table. 

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      ...
   ],


single_item_name *
------------------

This is the singular version of the type of data which is being dealt with in this panel.

.. code-block:: php 

   'panel_options'   => [

      'table'              => 'posts', 
      'single_item_name'   => 'Post', 
      ...
   ],



allow_bulk_delete
----------------- 

Setting this option to true will allow users to select and delete multiple items in list views (including filtered list views)



default_order_by
---------------- 

This option allows you to set the order in which records for this panel will be displayed. You can choose one or more database fields. You will use traditional SQL syntax.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'default_order_by' => 'title ASC, date DESC',
      ...
   ],



has_timestamps
-------------- 

If this option is set to true then the migrations and models generated for this panel/table will create and utilize Laravel Eloquent's ``created_at`` and ``updated_at`` timestamp fields.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'has_timestamps' => TRUE,
      ...
   ],



js_include
---------- 

This option allows you to specify a javascript file to include on all pages of this panel. It is great for sewing in your own functionality. jQuery is available on all pages as well.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'js_include' => '/js/my-own.js',
      ...
   ],



nestable_by
----------- 

This is a very powerful feature which will allow your users to reorder and hierarchically "nest" the records of this table. When enabled your users can drag and drop records to reorder them arbitrarily as well as "nest" them into a tree-like model.

.. image:: images/nested.png

.. code-block:: php 

   'panel_options'   => [

      'table' => 'pages',  
      'nestable_by' => ['position', 'parent_id', 'depth'],
      ...
   ],

The three array elements are (1) the field name which contains the overall sort-order (2) the field name which contains the ID of the parent of the record. Records on the top-level have a ``parent_id`` of ``0`` and (3) the depth level of the record. 

*Note: You do not need to also create ``position``, ``parent_id``, and ``depth`` elements in your panel's :ref:`element-options` configuration. Station will manage these for you.*



no_build
-------- 

If this option is set to true then the ``php artisan station:build`` command will simply skip this panel entirely when it attempts to create models and migrations. This is useful for panels where you want to use the ``override`` option and you have no need for a data model to be available.



no_data_alert
------------- 

This option, defined by an array, can be used to configure a special message to users of a panel which has no data. This can be useful for when you want to assist users on creating a type of data for the first time.

.. code-block:: php 

   'panel_options'   => [
      'table'              => 'posts',
      'single_item_name'   => 'Blog Post',
      'no_data_alert'      => [

         'header'    => 'You have no blog posts yet',
         'body'      => 'Go ahead and create your first blog post now!'
      ]
   ],



no_data_force_create
-------------------- 

When this option is set to true it will redirect a user who is trying to access a panel's (initial) list view to the panel's create view instead.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'no_data_force_create' => TRUE,
      ...
   ],


.. _override:

override
-------- 

This option allows you to completely override the functionality of a specific panel using a controller and method from your Laravel app. For an example of this, look at the ``welcome`` panel which shipped with Station.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'override' => ['L' => 'MyControllerName@method_name'],
      ...
   ],

The ``L`` above means that this will override the (initial) list view of your panel. However you can override the ``U`` (update) function instead and just leave the list view as-is using ``'override' => ['U' => 'MyControllerName@method_name'],``. When using the update override, the record your user is attempting to modify will be passed as data to your controller method automatically.



preview_url
----------- 

This option allows you to specify a array template for generating the url for a button which will become visible in the update view of every record in this panel. 

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'preview_url' => ['http://www.domain.com/post/', 'posts.id', '/preview'],
      ...
   ],

The elements of this array will concatenate to form the preview URL. When one of the array's elements is in the format ``table_name.field_name`` it will be replaced by the actual record's value. So the example above might produce ``http://www.domain.com/post/9999/preview`` and a button which looks like the one below will appear on your panel's update pages:

.. image:: images/preview-url.png



reorderable_by
--------------

This option allows you to specify a field name to use as your table's "position" field. This is a field which is used to store an arbitrary, user-defined sorting-order for the records in the table. When enabled, your users will be able to drag and drop records to reorder them within the list view of this panel. Each time a user reorders the records, all of the values for the field you specify will be re-written from 0 through X. 

.. code-block:: php 

   'panel_options'   => [

      'table' => 'categories',  
      'reorderable_by' => 'position',
      'default_order_by' => 'position',
      ...
   ],

*Note: You do not need to also create a ``position`` element in your panel's :ref:`element-options` configuration. Station will manage this for you.*


where
-----

This option allows you to append a SQL ``where`` clause onto the standard query which retrieves the data for this panel.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'posts',  
      'where' => 'title LIKE "%robot%"',
      ...
   ],

This is also a good opportunity to pass in :ref:`config-variables` or :ref:`custom-config-variables` if those are relevant to your app.

.. code-block:: php 

   'panel_options'   => [

      'table' => 'employees', 
      'where' => 'company_id IN (%user_company_ids%)',
   ],



