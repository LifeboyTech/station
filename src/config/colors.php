<?php

	/**
	* This is the panel-level configuration for Tags : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'colors',	//if no tablename, uses panel name
				'single_item_name'	=> 'Color',
				'has_timestamps'	=> FALSE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'name'
			],
			
			'elements'	=> [

				'name'	=> [
					'label'			=> 'Name',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|unique:colors,name|between:3,90',
					'display'		=>	'CRUDL'
				]


			]
			

	];