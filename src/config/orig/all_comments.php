<?php

	/**
	* This is the panel-level configuration for Comments : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'comments',	//if no tablename, uses panel name
				'single_item_name'	=> 'Comment',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'created_at'
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'body'	=> [
					'label'			=> 'Body',
					'type'			=> 'textarea',
					'length'		=> 70000, 
					'attributes'	=> '',
					'rules'			=>	'required|alpha_dash',
					'display'		=>	'CRUD'
				],
				'owner'	=> [
					'label'			=> 'Author',
					'type'			=> 'integer',
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL',
					'data'			=> [				
						'join'		=> FALSE,
						'relation'	=> 'belongsTo',
						'table'		=> 'posts',
						'pivot'		=> 'post'
					]
				]
			]
	];