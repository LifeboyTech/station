<?php

	return [
			'panel_options'	=> [
				'table'					=> 'stores',	
				'single_item_name'		=> 'Store',
				'where' 				=> 'user_id = "%user_id%"',
				'has_timestamps'		=> TRUE,
				'default_order_by'		=> 'name',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'Let\'s Add Your Stores!',
					'body' 		=> 'If you have at least one store, please add it below. After saving, you can add more.'
				]
			],
			
			'elements'	=> [
				
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
				'store_images'	=> [
					'label'			=> 'Images',
					'type'			=> 'subpanel',
					'display'		=> 'CU',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'hasMany',
						'table'		=> 'store_images',
						'key' 		=> 'store_id'
					]
				],
				'user_id'	=> [
					'label'			=> 'User ID',
					'type'			=> 'hidden',
					'default'		=> '%user_id%',
					'rules'			=> 'required',
					'display'		=> 'CU'
				],
				'address'	=> [
					'label'			=> 'Physical Address',
					'help' 			=> 'We\'ll use our hi-tech mapping system to find it!',
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