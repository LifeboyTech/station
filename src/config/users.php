<?php

	return [
			'panel_options'	=> [
				'table'				=> 'users',	//if no tablename, uses panel name
				'single_item_name'	=> 'User',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'last_name ASC'
			],
			
			'elements'	=> [

				'username'	=> [
					'label'			=> 'Username',
					'type'			=> 'text',
					'length'		=> 30,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|alpha_dash|unique:users,username|between:4,30', // this is wildcard for this table.
					'display'		=>	'CRUDL'
				],
				'password'	=> [
					'label'			=> 'Password',
					'type'			=> 'password',
					'length'		=> 255, // important. must be greater than 60 characters
					'attributes'	=> '',
					'rules'			=>	'required|alpha_dash|between:4,30',
					'display'		=>	'C'
				],
				'email'	=> [
					'label'			=> 'Email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|email|unique:users,email|max:90',
					'display'		=>	'CRUD'
				],
				'paypal_email'	=> [
					'label'			=> 'Paypal Email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'email|unique:users,paypal_email|max:90',
					'display'		=>	'CRUD'
				],
				'subdomain'	=> [
					'label'			=> 'Dedicated Subdomain',
					'type'			=> 'text',
					'append' 		=> '.whereyart.net',
					'length'		=> 255,
					'attributes'	=> 'unique',
					'rules'			=>	'unique:users,subdomain|between:3,30',
					'display'		=>	'CRUD'
				],
				'first_name'	=> [
					'label'			=> 'First Name',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'required|max:90',
					'display'		=>	'CRUDL'
				],
				'last_name'	=> [
					'label'			=> 'Last Name',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'required|max:90',
					'display'		=>	'CRUDL'
				],
				'img_user_hero' => [
					'label'			=> 'Artist Hero Image',
					'help' 			=> 'This is the big image on the artist page',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large Version','size'=>'483x0']			
					]
				],
				'avatar' => [
					'label'			=> 'Artist Avatar',
					'type'			=> 'image',
					'rules'			=> '',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'small'		=> ['label'=>'Teardrop Version','size'=>'91x91']			
					]
				],
				'brief_bio'	=> [
					'label'			=> 'Brief Bio',
					'help' 			=> 'Artist Biography.',
					'type'			=> 'textarea',
					'rows' 			=> 10,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'locations'	=> [
					'label'			=> 'Locations',
					'type'			=> 'textarea',
					'rows' 			=> 6,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'faq'	=> [
					'label'			=> 'QnA',
					'type'			=> 'textarea',
					'rows' 			=> 6,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'shipping_policy'	=> [
					'label'			=> 'Shipping Policy',
					'type'			=> 'textarea',
					'rows' 			=> 6,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'birthplace'	=> [
					'label'			=> 'Neighborhood',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'business_name'	=> [
					'label'			=> 'Business Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'phone'	=> [
					'label'			=> 'Phone',
					'type'			=> 'text',
					'format' 		=> 'phone',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'skills'	=> [
					'label'			=> 'Medium',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUDL',
					'is_filterable' => TRUE,
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'skills',
						'pivot'		=> 'skills',
						'display'	=> 'skills.name',
						'order'		=> 'skills.name'
					]
				],
				'paid_until'	=> [	// for subscriptions, person is paid up to 2013-10-31 for example
					'label'			=> 'Paid up to',
					'type'			=> 'date',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'groups'	=> [
					'label'			=> 'Groups',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUDL',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'groups',
						'pivot'		=> 'groups',
						'display'	=> 'groups.name',
						'order'		=> 'groups.name'
					]
				],
				'is_published'	=> [
					'label'			=> 'Published?',
					'type'			=> 'boolean',
					'rules'			=> '',
					'display'		=> 'CRUDL',
					'default'  		=> '1',
					'data'			=> [
						'options'		=> [
							0 => 'Off',
							1 => 'On'
						]
					]
				],
			]
	];