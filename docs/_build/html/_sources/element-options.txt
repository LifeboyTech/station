Element Options 
=============== 

The ``elements`` key of the array defined in any of the files at ``/config/packages/lifeboy/station`` represents the list of elements which are available to users of Station. 

Generally, each element is mapped to a specific database field. However, this is not always the case. Some elements are "virtual". See below for the full documentation on configuring elements.

.. note:: all options marked with an * are required


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

**text**

   * This will generate a VARCHAR(255) database field. 
   * The input for user manipulation is a simple ``<input type="text">``

   .. image:: images/label.png

**boolean**

   * This will generate a TINYINT(1) database field. 
   * The input for user manipulation is a toggle "on/off" switch (which masks a ``<input type="checkbox">``).

   .. image:: images/boolean.png

**image** 
   
   * This will generate a VARCHAR(255) database field.
   * The input for user manipulation is a special image uploader w/ crop tool mechanism.
   * See the :ref:`config-images` option for more details on configuration, sizing and cropping.

   .. image:: images/image.png

**tags**
   
   * This will generate a VARCHAR(255) database field.
   * The input for user manipulation is a special tagging interface.
   * The field data is written to the database as comma delimited values.

   .. image:: images/tags.png

**select**

   * This can be used in conjunction with another table or with static data (see the :ref:`data-type` option).
   * The input for user-manipulation uses the wonderful `Chosen <https://harvesthq.github.io/chosen/>`_ library which contains a dropdown with search bar

   .. image:: images/select.png

**multiselect**

   * This can only be used when a relationship with another table has been defined (see the :ref:`data-type` option).
   * Data will be written to the database via a pivot table which is auto-generated via :ref:`build-command`.
   * The input for user-manipulation uses the wonderful `Chosen <https://harvesthq.github.io/chosen/>`_ library which contains a taggable dropdown with search bar

   .. image:: images/multiselect.png

**radio**
   
   * This will generate a VARCHAR(255) database field.
   * This can be used in conjunction with another table or with static data (see the :ref:`data-type` option).
   * The input for user-manipulation uses enhanced radio buttons (masking standard ``<input type="radio">`` inputs).

   .. image:: images/radio.png

**virtual**
   
   * Virtual type fields do not actually map to real database fields.
   * They are often used in conjunction with the ``concat`` option in order to create links in a list view which require one or more *other* fields from the same record.

   .. code-block:: php 

      'permalink' => [
         'label'        => 'Permalink',
         'type'         => 'virtual',
         'concat'       => '"<a href=\'http://www.domain.com/faq#answer-", id, "\' target=\'_blank\'>Preview</a>"',
         'display'      => 'L'
      ],


.. _data-type:

data
---- 



.. _config-images:

sizes
-----