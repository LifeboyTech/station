<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'catalog_preferences',	//if no tablename, uses panel name
				'single_item_name'		=> 'Catalog Sync Preference',
				'where' 				=> 'user_id = "%user_id%"',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'			=> 'name',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'We\'re Building a Robust Catalog Publishing System',
					'body' 		=> 'In December 2013 we will be rolling out a platform which will enable vendors to send LocalGear their '
									. 'catalog data easily. We are gathering feedback from as many vendors as possible to determine their '
									. 'preferences regarding the uploading of catalog data and content.'
				]
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'storage_format'	=> [
					'label'			=> 'Current Format Used',
					'help' 			=> 'What is your current format for storing catalog data?',
					'type'			=> 'radio',
					'rules'			=>	'required',
					'display'		=>	'CRUDL',
					'data'			=> [
						'options'		=> [
							0 => 'OIA Standard',
							1 => 'OIA Standard in XML Feed',
							2 => 'Google Format',
							3 => 'I don\'t know',
							4 => 'Other'
						]
					]
				],
				'publish_method'	=> [
					'label'    		=> 'Publish Method',
					'help'			=> 'Preferred method for publishing catalog content?',
					'type'			=> 'radio',
					'rules'			=>	'required',
					'display'		=>	'CRUDL',
					'data'			=> [
						'options'		=> [
							0 => 'Manual Upload',
							1 => 'Allow LocalGear to download',
							2 => 'Not sure',
							3 => 'Other'
						]
					]
				],
				'contact_name'	=> [
					'label'			=> 'Company Contact Name',
					'help' 			=> 'Who may we contact about publishing your catalog?',
					'type'			=> 'text',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUDL'
				],
				'contact_email'	=> [
					'label'			=> 'Contact Email',
					'help' 			=> '',
					'type'			=> 'text',
					'attributes'	=> '',
					'rules'			=> 'email',
					'display'		=> 'CRUDL'
				],
				'contact_phone'	=> [
					'label'			=> 'Contact Phone',
					'help' 			=> '',
					'format'   		=> 'phone',
					'type'			=> 'text',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUDL'
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