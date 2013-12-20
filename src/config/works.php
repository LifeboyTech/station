<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'works',	// required
				'single_item_name'		=> 'Work',	
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'		=> 'name', 	// Column to order by
				'js_include' 			=> '' 		// optional path to JS file to include [use leading slash]
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
				'artist_id'	=> [
					'label'			=> 'Artist',
					'type'			=> 'select',
					'display'		=>	'CRUDL',
					'rules'			=> 'required',
					'is_filterable' => TRUE,
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsTo',
						'table'		=> 'users',
						'display'	=> ['users.first_name', ' ', 'users.last_name'],
						'order'		=> 'users.last_name'
					]
				],	
				'image' => [
					'label'			=> 'Primary Image',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUDL',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'486x0'],
						'medium'	=> ['label'=>'Medium','size'=>'239x0'],
						'small'		=> ['label'=>'Small','size'=>'225x0']
					]
				],
				'work_images'	=> [
					'label'			=> 'Additional Images',
					'type'			=> 'subpanel',
					'display'		=> 'CU',
					'permissions' 	=> 'CRUD',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'hasMany',
						'table'		=> 'work_images',
						'key' 		=> 'work_id'
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
					'display'		=> 'CRUD'
				],
				'dimensions'	=> [
					'label'			=> 'Dimensions',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
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
					'label'			=> 'Medium',
					'type'			=> 'select',
					'display'		=>	'CRUDL',
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
					'display'		=>	'CRUDL',
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
					'label'			=> 'Quantity Available For Sale',
					'type'			=> 'text',
					'format' 		=> 'spinner',
					'rules'			=>	'numeric',
					'display'		=>	'CRUD'
				],
				'work_options'	=> [
					'label'			=> 'Alternate Purchase Options',
					'type'			=> 'subpanel',
					'display'		=> 'CU',
					'permissions' 	=> 'CRUD',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'hasMany',
						'table'		=> 'work_options',
						'key' 		=> 'work_id'
					]
				]
			]
	];