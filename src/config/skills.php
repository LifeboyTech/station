<?php

	/**
	* This is the panel-level configuration for Skills : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'skills',	//if no tablename, uses panel name
				'single_item_name'	=> 'Skill',
				'has_timestamps'	=> FALSE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'position',
				'reorderable_by' 	=> 'position'
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'name'	=> [
					'label'			=> 'Name',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|unique:skills,name|between:3,90',
					'display'		=>	'CRUDL'
				]


			]
			

	];