<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'users',	//if no tablename, uses panel name
				'single_item_name'	=> 'Account',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'username',
				'where' 			=> 'id = "%user_id%"'
			],
			
			'elements'	=> [
				// These are the components that make up this panel
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
					'display'		=>	'CU'
				],
				'email'	=> [
					'label'			=> 'Email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|email|unique:users,email|max:90',
					'display'		=>	'CRUDL'
				],
				'paypal_email'	=> [
					'label'			=> 'Paypal Email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|email|unique:users,paypal_email|max:90',
					'display'		=>	'CRUDL'
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
				'brief_bio'	=> [
					'label'			=> 'Brief Bio',
					'help' 			=> 'Artist Biography.',
					'type'			=> 'textarea',
					'rows' 			=> 3,
					'attributes'	=> '',
					'rules'			=> 'required',
					'display'		=> 'CRUD'
				],
				'phone'	=> [
					'label'			=> 'Phone',
					'type'			=> 'text',
					'format' 		=> 'phone',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'skills'	=> [
					'label'			=> 'Medium',
					'type'			=> 'multiselect',
					'multiple'		=> TRUE,
					'display'		=>	'CRUDL',
					'data'			=> [
						'join'		=> TRUE,
						'relation'	=> 'belongsToMany',
						'table'		=> 'skills',
						'pivot'		=> 'skills',
						'display'	=> 'skills.name',
						'order'		=> 'skills.name'
					]
				]
			]
	];