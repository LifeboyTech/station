<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'inventory_preferences',	//if no tablename, uses panel name
				'single_item_name'		=> 'Inventory Preference',
				'where' 				=> 'user_id = "%user_id%"',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'name',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'We\'re Building a Robust Inventory Sync Tool',
					'body' 		=> 'In December 2013 we will be rolling out an inventory management tool which will enable your company '
								. 'to upload your current in-store inventory. Please let us know how you currently store your inventory '
								. 'and how you would wish to keep it in sync with the LocalGear system.'
				]
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'method'	=> [
					'label'			=> 'What is your preferred method for sending LocalGear inventory data?',
					'type'			=> 'radio',
					'rules'			=>	'required',
					'display'		=>	'CRUDL',
					'data'			=> [
						'options'		=> [
							0 => 'FTP Daily',
							1 => 'Direct Connection From Point of Sale',
							2 => 'Manual Updates via LocalGear Control Panel',
							3 => 'Other'
						]
					]
				],
				'comments'	=> [
					'label'			=> 'Comments or Concerns?',
					'help' 			=> 'Please let us know!',
					'type'			=> 'textarea',
					'rows' 			=> 8,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'user_id'	=> [
					'label'			=> 'User ID',
					'type'			=> 'hidden',
					'default'		=> '%user_id%',
					'rules'			=> 'required',
					'display'		=> 'CRUDL'
				]
			]
	];