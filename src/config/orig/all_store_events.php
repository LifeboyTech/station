<?php

	/**
	* This is the panel-level configuration for Posts : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'store_events',	//if no tablename, uses panel name
				'single_item_name'	=> 'Event',
				'where' 			=> '',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'created_at'

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
				]

			]
			

	];