<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'stores',	//if no tablename, uses panel name
				'single_item_name'		=> 'Store',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'			=> 'name'
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'name'	=> [
					'label'			=> 'Store Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'industries'	=> [
					'label'			=> 'Primary Industries',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUDL',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'industries',
						'pivot'		=> 'industries',
						'display'	=> 'industries.name',
						'order'		=> 'industries.name'
					]
				],
				'address'	=> [
					'label'			=> 'Physical Address',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'zip'	=> [
					'label'			=> 'Zip Code',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				]
			]
	];