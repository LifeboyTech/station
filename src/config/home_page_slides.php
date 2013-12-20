<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'home_page_slides',	//if no tablename, uses panel name
				'single_item_name'		=> 'Slide',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'position',
				'reorderable_by'		=> 'position'
			],
			
			'elements'	=> [
			
				'name'	=> [
					'label'			=> 'Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'sub_name'	=> [
					'label'			=> 'Sub-Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'artist_id'	=> [
					'label'			=> 'Related Artist',
					'type'			=> 'select',
					'display'		=>	'CRUDL',
					'rules'			=> '',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsTo',
						'table'		=> 'users',
						'display'	=> ['users.first_name', ' ', 'users.last_name'],
						'order'		=> 'users.last_name'
					]
				],	
				'image_1' => [
					'label'			=> 'Primary Image',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'960x733']
					]
				],
				'image_2' => [
					'label'			=> 'Top-Right Image',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'437x179']
					]
				],
				'image_3' => [
					'label'			=> 'Bottom-Right Image',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'437x550']
					]
				]
			]
	];