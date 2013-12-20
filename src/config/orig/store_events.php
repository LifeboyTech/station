<?php

	/**
	* This is the panel-level configuration for Posts : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'store_events',	//if no tablename, uses panel name
				'single_item_name'	=> 'Event',
				'where' 			=> 'user_id = "%user_id%"',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'created_at',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'Post Events About Ongoings At Your Stores',
					'body' 		=> 'Keep your customers informed about special events, trainings, and more.'
				]

			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'title'	=> [
					'label'			=> 'Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required|between:4,90', 
					'display'		=>	'CRUDL'
				],
				'date'	=> [
					'label'			=> 'Date',
					'type'			=> 'date',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	'CRUD'
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