<?php

	/**
	* This is the panel-level configuration for Posts : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'posts',	//if no tablename, uses panel name
				'single_item_name'	=> 'Journal Post',
				'where' 			=> 'user_id = "%user_id%"',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'created_at',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'Connect With Your Customers!',
					'body' 		=> 'By updating your company journal you can keep your customers in the loop. '
								. 'We will soon be adding slideshow tools, video controls and more.'
				]

			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'title'	=> [
					'label'			=> 'Post Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required|between:4,90', 
					'display'		=>	'CRUDL'
				],
				'body'	=> [
					'label'			=> 'Body',
					'type'			=> 'textarea',
					'rows' 			=> 20, 
					'attributes'	=> '',
					'rules'			=>	'required|alpha_dash',
					'display'		=>	'CRUD'
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