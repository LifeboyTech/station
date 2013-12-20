<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'industries',	//if no tablename, uses panel name
				'single_item_name'		=> 'Industry',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'no_data_force_create' 	=> TRUE,
				'default_order_by'		=> 'position',
				'reorderable_by' 		=> 'position'
			],
			
			'elements'	=> [

				'name'	=> [
					'label'			=> 'Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				]
			]
	];