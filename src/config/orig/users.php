<?php

	/**
	* This is the panel-level configuration for Users : Station
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'users',	//if no tablename, uses panel name
				'single_item_name'	=> 'User',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'username'
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
					'display'		=>	'C'
				],
				'email'	=> [
					'label'			=> 'Email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|email|unique:users,email|max:90',
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
				'phone'	=> [
					'label'			=> 'Phone',
					'type'			=> 'text',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
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
						'table'		=> 'groups',
						'pivot'		=> 'groups',
						'display'	=> 'groups.name',
						'order'		=> 'groups.name'
					]
				]

			]
			

	];