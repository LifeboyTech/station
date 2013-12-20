<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'works',	//if no tablename, uses panel name
				'single_item_name'		=> 'Work Listing',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'name',
				'where' 				=> 'artist_id = "%user_id%"',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'Welcome to your Where Y\'Art Artist Profile!',
					'body' 		=> 'Start by adding works for sale to your profile right here! '
					 			. 'All new works for sale need to be approved by our staff before they will post to the site. '
					 			. 'Please allow 2-3 days for each work to be published.'
				]
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
				'image' => [
					'label'			=> 'Primary Image',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CL',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'486x0'],
						'medium'	=> ['label'=>'Medium','size'=>'239x0'],
						'small'		=> ['label'=>'Small','size'=>'225x0']
					]
				],
				'story'	=> [
					'label'			=> 'Back Story',
					'help' 			=> '',
					'type'			=> 'textarea',
					'rows' 			=> 9,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'description'	=> [
					'label'			=> 'Media / Materials',
					'help' 			=> '',
					'type'			=> 'textarea',
					'rows' 			=> 4,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUDL'
				],
				'dimensions'	=> [
					'label'			=> 'Dimensions',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'year'	=> [
					'label'			=> 'Year',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'skills'	=> [
					'label'			=> 'Primary Skill Used',
					'type'			=> 'select',
					'display'		=>	'CRUD',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsTo',
						'table'		=> 'skills',
						'display'	=> 'skills.name',
						'order'		=> 'skills.name'
					]
				],				
				'tags'	=> [
					'label'			=> 'Tags',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUD',
					'is_filterable' => TRUE,
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'tags',
						'pivot'		=> 'tags',
						'display'	=> 'tags.name',
						'order'		=> 'tags.name'
					]
				],
				'colors'	=> [
					'label'			=> 'Color Options',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUD',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'colors',
						'pivot'		=> 'colors',
						'display'	=> 'colors.name',
						'order'		=> 'colors.name'
					]
				],
				'price'	=> [
					'label'			=> 'Price',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_price'	=> [
					'label'			=> 'Shipping Price',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'quantity_available'	=> [
					'label'			=> 'Quantity Available',
					'type'			=> 'text',
					'format' 		=> 'spinner',
					'rules'			=>	'numeric',
					'display'		=>	'CRUDL'
				],
				'artist_id'	=> [
					'label'			=> 'Artist ID',
					'type'			=> 'hidden',
					'default'		=> '%user_id%',
					'rules'			=> 'required',
					'display'		=> 'CRUD'
				]
			]
	];