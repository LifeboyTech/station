
.. _models:

Working with Station Models 
===========================

Every time you run :ref:`build-command` all of your models (at least all of the models which are associated with Station panels) get regenerated. You might be thinking, "Well that sucks, what if I want to add my own methods to those models?!"". Guess what? That is not a problem. Station's models carefully avoid any custom code you've written and instead only replace Station's own boilerplate class variables and methods in your models, leaving your own work intact.

.. code-block:: php 

	<?php namespace App\Models; 

	class Document extends \Eloquent {

		//GEN-BEGIN

		protected $table = 'documents';
		protected $guarded = array('id');


		//GEN-END

		// Feel free to add any new code after this line

	}

This is an example of the model that is generated for a ``documents`` panel after running :ref:`build-command`. Any code that is added after ``//GEN-END`` will be safely ignored upon regeneration. So, feel free to add your own scopes, accessors, mutators, and other Laravel Eloquent goodies!
