<?php

	/**
	* This is the panel-level configuration for Media : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'media',	//if no tablename, uses panel name
				'single_item_name'	=> 'Media',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'has_position'		=> FALSE,
				'default_order_by'	=> 'created_at',
				'no_data_force_create' 	=> FALSE

			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'ownertype'	=> [
					'label'			=> 'Owner Type',
					'type'			=> 'integer',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				],
				'ownerid'	=> [
					'label'			=> 'Owner Id',
					'type'			=> 'integer',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				],
				'filesize'	=> [
					'label'			=> 'Filesize',
					'type'			=> 'integer',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				],
				'filename'	=> [
					'label'			=> 'Filename',
					'type'			=> 'text',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				],
				'used_at'	=> [
					'label'			=> 'Panels that use this media element',
					'type'			=> 'text',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				],
				'filetype'	=> [
					'label'			=> 'Type of file',
					'type'			=> 'integer',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	''
				]

			]
			

	];