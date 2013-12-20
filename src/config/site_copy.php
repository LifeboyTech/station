<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'site_copy',	//if no tablename, uses panel name
				'single_item_name'		=> 'Page',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'position',
				'reorderable_by'		=> 'position',
				'where'					=> 'area = "0"'
			],
			
			'elements'	=> [
			
				'name'	=> [
					'label'			=> 'Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'description'	=> [
					'label'			=> 'Body',
					'type'			=> 'textarea',
					'rows' 			=> 26,
					'rules'			=>	'required',
					'display'		=>	'CRUD'
				],
				'area'	=> [
					'label'			=> 'Area',
					'type'			=> 'hidden',
					'rules'			=>	'required',
					'display'		=>	'CRUD',
					'default' 		=> '0'
				]
			]
	];