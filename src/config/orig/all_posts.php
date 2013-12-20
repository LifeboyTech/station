<?php

	/**
	* This is the panel-level configuration for Posts : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'posts',	//if no tablename, uses panel name
				'single_item_name'	=> 'Journal Post',
				'where' 			=> '',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'created_at'
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
				]
			]
	];