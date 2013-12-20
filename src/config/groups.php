<?php

	/**
	* This is the panel-level configuration for Groups : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'groups',	//if no tablename, uses panel name
				'single_item_name'	=> 'Group',
				'has_timestamps'	=> FALSE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'name',
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'name'	=> [
					'label'			=> 'Name',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|unique:groups,name|between:3,90',
					'display'		=>	'CRUDL'
				]


			]
			

	];