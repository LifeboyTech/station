.. _element-option:

Element Options 
=============== 

The ``elements`` key of the array defined in any of the files at ``/config/packages/lifeboy/station`` represents the list of elements which are available to users of Station. 

Generally, each element is mapped to a specific database field. However, this is not always the case. Some elements are "virtual". See below for the full documentation on configuring elements.

.. note:: 

   *All options marked with a * are required!*

   **Important:** You do not need to create an ``id`` element in your panels. Station assumes that any panel which is mapped to a :ref:`table` has an ``id`` field and it will auto-generate this field for you as your table's primary key and index.


(key name) *
------------ 

The key of each element (unless the element ``type`` is ``virtual``) is the name of the database field of this panel's :ref:`table`.

.. code-block:: php 

   'first_name'   => [

      'label' => 'First Name',
      'type' => 'text',
      ...
   ],

In this example, ``first_name`` is the name of the database field.



label * 
-------

This is the user-facing "name" of the field. This name will appear in a number of places. (1) As the input label in the create and update form. (2) on the top of a list view column (if this element has been given list permissions) and (3) in validation error messages, when a user has not fulfilled validation requirements for this element.

.. code-block:: php 

   'first_name'   => [

      'label' => 'First Name',
      'type' => 'text',
      ...
   ],

.. image:: images/label.png



type *
------

This is the "type" of element and there are numerous options. Your choice of element type is based primarily on which kind of browser input you wish to use, however it also influences the field type used in the auto-generated database migrations:

.. code-block:: php 

   'is_active'   => [

      'label' => 'Activated?',
      'type' => 'boolean',
      ...
   ],

text
^^^^

   * This will generate a VARCHAR(255) database field. 
   * The input for user manipulation is a simple ``<input type="text">``

   .. image:: images/label.png

integer
^^^^^^^

   * This will generate a INT(12) database field. 
   * The input for user manipulation is a simple ``<input type="text">``

boolean
^^^^^^^

   * This will generate a TINYINT(1) database field. 
   * The input for user manipulation is a toggle "on/off" switch (which masks a ``<input type="checkbox">``).

   .. image:: images/boolean.png


.. _type-image:

image
^^^^^
   
   * This will generate a VARCHAR(255) database field.
   * The input for user manipulation is a special image uploader w/ crop tool mechanism.
   * See the :ref:`config-images` option for more details on configuration, sizing and cropping.

   .. image:: images/image.png

tags
^^^^
   
   * This will generate a VARCHAR(255) database field.
   * The input for user manipulation is a special tagging interface.
   * The field data is written to the database as comma delimited values.

   .. image:: images/tags.png

select
^^^^^^

   * This can be used in conjunction with another table or with static data (see the :ref:`data-type` option).
   * The input for user-manipulation uses the wonderful `Chosen <https://harvesthq.github.io/chosen/>`_ library which contains a dropdown with search bar

   .. image:: images/select.png

multiselect
^^^^^^^^^^^

   * This can only be used when a relationship with another table has been defined (see the :ref:`data-type` option).
   * Data will be written to the database via a pivot table which is auto-generated via :ref:`build-command`.
   * The input for user-manipulation uses the wonderful `Chosen <https://harvesthq.github.io/chosen/>`_ library which contains a taggable dropdown with search bar

   .. image:: images/multiselect.png

radio
^^^^^
   
   * This will generate a VARCHAR(255) database field.
   * This can be used in conjunction with another table or with static data (see the :ref:`data-type` option).
   * The input for user-manipulation uses enhanced radio buttons (masking standard ``<input type="radio">`` inputs).

   .. image:: images/radio.png

virtual
^^^^^^^
   
   * Virtual type fields do not actually map to real database fields.
   * No field will be generated from :ref:`build-command`.
   * They are often used in conjunction with the ``concat`` option in order to create links in a list view which require one or more *other* fields from the same record.

   .. code-block:: php 

      'permalink' => [
         'label'        => 'Permalink',
         'type'         => 'virtual',
         'concat'       => '"<a href=\'http://www.domain.com/faq#answer-", id, "\' target=\'_blank\'>Preview</a>"',
         'display'      => 'L'
      ],

date / datetime
^^^^^^^^^^^^^^^

   * These will generate ether a DATE() or DATETIME() database field.
   * The input for user-manipulation is a calendar day-picker with or without a time-picker.
   
   .. image:: images/date.png

float
^^^^^

   * This will generate a FLOAT(10,2) database field.

   .. code-block:: php 

      'tax'  => [
         'label'        => 'Tax on Clothing Exemption Cap',
         'type'         => 'float',
         'format'       => 'money',
         'prepend'      => '$',
         'attributes'   => '',
         'rules'        => '',
         'display'      => 'CRUD'
      ],

   The above example would produce:

   .. image:: images/float.png

textarea
^^^^^^^^

   * This will generate a TEXT() database field.

   .. code-block:: php 

      'description'  => [
         'label'        => 'Description',
         'helper'       => 'markdown',
         'type'         => 'textarea',
         'rows'         => 18,
         'embeddable'   => TRUE,
         'display'      => 'CRUD'
      ],

   .. image:: images/description.png


hidden
^^^^^^

   * This will generate a VARCHAR(255) database field.
   * As the name suggests this will simply render a ``<input type="hidden">`` input in your forms.
   * This can be very useful when used in conjunction with the ``default`` option.

password
^^^^^^^^

   * This will generate a VARCHAR(255) database field. 
   * The input for user manipulation is a simple ``<input type="password">``

subpanel
^^^^^^^^

   * This is a way to "nest" a panel within another panel.
   * You will need to configure the ``data`` option (see the :ref:`data-type` option for more details) in order to define which panel becomes nested and how the two panels are linked.

   .. image:: images/subpanel.png



allow_upsize
------------ 

This option is only available to elements using the type ":ref:`type-image`". When set to true, a user uploading an image is allowed to use a smaller image size than the largest dimension expected. The image will be magnified to fit the largest dimension. See more on sizing using the :ref:`config-images` option.


attributes 
---------- 

:ref:`build-command` utilizes the wonderful `Laracast Generators <https://github.com/laracasts/Laravel-5-Generators-Extended>`_ package to generate migrations for your panels. If you add pipe-delimited arguments to the ``attributes`` option, those arguments will be passed to the generator as `specific schema <https://github.com/laracasts/Laravel-5-Generators-Extended#migrations-with-schema>`_.

.. code-block:: php 

   'email'   => [

      'label' => 'Email',
      'type' => 'text',
      'attributes' => 'unique|index|default("foo@example.com")',
      ...
   ],

Note that ``attributes`` only affect the database schema and have no other affect on panel validation behaviors. To control panel validation behaviors use the :ref:`rules` option.



.. _data-type:

data
---- 

This option defines how the data for this element is populated. It is required whenever you use the element type ``select``, ``multiselect``, ``radio`` or ``subpanel``.

1. Static Data Options
^^^^^^^^^^^^^^^^^^^^^^

   * You want to present a list of static options for a user to choose from
   * You are using element ``'type' => 'select'`` or ``'type' => 'radio'``
   * The input options will be pre-populated using the array values you supply
   * The ``options`` array *values* will be seen/chosen by the user, however the *keys* will be saved to the database.

   .. code-block:: php 

      'favorite_animal'   => [
         'label'          => 'Your Favorite Animal',
         'type'           => 'radio', // <=== this works for `select` as well
         'default'        => '0',
         'is_filterable'  => TRUE,
         'data'           => [
            'options' => [
               0 => 'None',
               1 => 'Pig',
               2 => 'Ocelot',
               3 => 'Llama',
            ]
         ]
      ],

2. Foreign Table Data Lookup
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

   * You want to present a list of options for a user to choose from, but is populated using a foreign table's data
   * You are using element ``'type' => 'select'``, ``'type' => 'radio'`` or ``'type' => 'multiselect'``
   * The input options will be pre-populated using the data from the foreign table
   * The foreign table's chosen ``id`` value will be saved to this table or a pivot table.

   Example using ``select``:

   .. code-block:: php 

      'favorite_animal'   => [
         'label'        => 'Favorite Animal',
         'type'         => 'select',
         'data'         => [
            'join'      => TRUE,
            'relation'  => 'belongsTo', // <== This relationship is written to the auto-generated model
            'table'     => 'animals',
            'display'   => ['animals.name', ' ' ,'(', 'animals.genus', ' : ', 'animals.species', ')'],
            'no_model'  => TRUE // only use this if you want to avoid writing this relationship to the model
         ]
      ],

   Example using ``multiselect``:

   .. code-block:: php 

      'favorite_animals'   => [
         'label'        => 'Favorite Animals',
         'type'         => 'multiselect',
         'data'         => [
            'join'      => TRUE,
            'relation'  => 'belongsToMany', // <== This relationship is written to the auto-generated model
            'table'     => 'animals',
            'pivot'     => 'animals',
            'display'   => 'animals.name',
            'order'     => 'animals.name'
         ]
      ],

   It is important that you specify a ``display`` value so that Station knows which of the foreign table's fields to use to display in the dropdown or on the radio buttons. Notice that you can provide an array for ``display`` which will concatenate field names and your own strings. This allows you to create a display using multiple foreign table fields.

3. Subpanel Data
^^^^^^^^^^^^^^^^

   * You want to nest a subpanel within this panel so a user can create, update, delete, and reorder a foreign table's data from within this panel!
   * This makes foreign table data manipulation possible.

   .. code-block:: php 

      'comments' => [
         'label'        => 'Comments',
         'type'         => 'subpanel',
         'permissions'  => 'CRUD', // <== User has all permissions on this subpanel
         'data'         => [
            'join'      => TRUE,
            'relation'  => 'hasMany',
            'table'     => 'comments',
            'key'       => 'post_id' // <== This is the foreign key, which will be auto-generated by Station's build command
         ]
      ],

   When a subpanel is defined like this, Station will look for another panel configuration file called ``comments.php``. That panel is configured just as you would configure a non-nested panel. You can even use the ``reorderable_by`` option in your subpanel so the user can reorder/sort the subpanel's data right from the parent panel! 

   *Note: you do not need to create an element for the foreign key when you make your subpanel's configuration file. Station will create it for you.*


default
------- 

Use this to set a default value for an element. This value will be first selected in a create or update form.

.. code-block:: php 

   'favorite_animal'   => [

      'label' => 'Your Favorite Animal',
      'type' => 'text',
      'default' => 'Panda',
      ...
   ],


.. _display:

display
------- 

This option informs Station when to display this element. You may indicate one or more of the following letters: **C.R.U.D.L**.

.. code-block:: php 

   C = Create 
   R = Read 
   U = Update 
   D = Delete 
   L = List 

.. code-block:: php 

   'favorite_animal'   => [

      'label' => 'Your Favorite Animal',
      'type' => 'text',
      'display' => 'CRUDL' // <=== This element will appear in all views & controls
      ...
   ],

   'favorite_movie'   => [

      'label' => 'Your Favorite Movie',
      'type' => 'text',
      'display' => 'CRUD' // <=== This element will not appear in the list view
      ...
   ],



help 
---- 

This options allows you to set some "helper" text which will display next to the element input in the create and update views. 

.. code-block:: php 

   'bio'   => [

      'label' => 'Company Bio',
      'type' => 'textarea',
      'help' => 'Optional. Just some brief, fun facts about your company',
      ...
   ],



is_filterable
------------- 

When this option is set to true and the element has type ``select`` and it has a :ref:`display` value allowing it to be shown in the list view (L), then a filter dropdown will appear allowing users to filter the list view by a value present in the table. The dropdown even contains a search tool, compliments of the `Chosen <https://harvesthq.github.io/chosen/>`_ library.

.. image:: /images/filter.png


prepend_icon
------------ 

This allows you to set a bootstrap glyphicon class name in order to prepend an icon to your elements input field. This only works with ``'type' => 'text'``.

.. code-block:: php 

   'url'   => [

      'label' => 'Web Address',
      'type' => 'text',
      'prepend_icon' => 'glyphicon glyphicon-globe',
      ...
   ],

.. image:: images/prepend.png


.. _rules:

rules 
----- 

This option configures the validation of an element. You must set the value to a pipe-delimited set of rules. The validation options include and are limited to the `Laravel Validation Rules <https://laravel.com/docs/5.2/validation#available-validation-rules>`_. You set the rules in exactly the same way that you would define them natively in Laravel.

.. code-block:: php 

   'title'   => [

      'label' => 'Post Title',
      'type' => 'text',
      'rules' => 'required|unique,posts,title|between:3,125',
      ...
   ],

**Note:** When using the ``unique`` rule, Station uses a ``,`` while Laravel requires a ``:``



.. _config-images:

sizes
-----

This option allows you to specify one or more image sizes and locations for uploaded images. Upon upload, only the name of the uploaded file will be saved to your database. The image itself will be resized, cropped, and saved to the locations you specify. If you wish, you can specify global application defaults in ":ref:`media-options`" so that you do not need to repeat the same sizes and locations in every panel.

.. code-block:: php 

   'logo' => [

      'label'        => 'Logo Image',
      'help'         => '(270 x 270 min)',
      'type'         => 'image',
      'display'      => 'CRUD',
      'allow_upsize' => TRUE,
      'sizes'     => [
         'original'  => ['label'=>'Original'],  
         'logo-300x150' => ['label'=>'300 x 150','size'=>'300x150', 'letterbox' => '#FFFFFF'],
         'logo-270x270' => ['label'=>'Fixed Width (270px)','size'=>'270x0'],
         'logo-180x180' => ['label'=>'Square Thumbnail','size'=>'180x180'],
      ]
   ],

In the example above, there are 4 different sizes (including an untouched, original version) which will be created upon upload. An associative array defines how the original, uploaded image will be manipulated and transmitted to your CDN server. *Note: currently only Amazon S3 is supported*. Here is the breakdown on how to configure the ``sizes`` option:

**(key)**

   * The key name, ex. ``logo-300x150`` is the name of the directory on the CDN server where the image will be saved.
   * If the directory does not exist it will be created automatically.

**label**
   
   * This is the title of the image version which will display in the crop and preview tool (see screenshot below). 
   * This can be any descriptive value you wish.

**size**

   * This defines the dimensions of the manipulation. Leaving this blank or undefined will save an unmodified version of the uploaded image. 
   * Setting a width only (``500x0``) or height only (``0x500``) will preserve the image's aspect ratio but will force the image to resize to the defined dimension.
   * Setting both a width and a height (``500x500``) will center-crop the image and allow your users to further crop it via Station's crop tool.

**letterbox**

   * When this is defined with a **size** of fixed width and height, the resulting crop will be an outer-crop instead of a center-crop.
   * Use this option to define the hex color value that will be used to fill any remaining space surrounding the cropped image.

Station's preview and crop tool:

.. image:: images/crop.png









