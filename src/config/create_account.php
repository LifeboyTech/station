<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'users',	
				'single_item_name'	=> 'Account',
				'has_timestamps'	=> TRUE,	
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
					'label'			=> 'Phone Number',
					'type'			=> 'text',
					'format' 		=> 'phone',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				

			]
	];