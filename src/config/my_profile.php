<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'				=> 'users',	//if no tablename, uses panel name
				'single_item_name'	=> 'Profile',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'username',
				'where' 			=> 'id = "%user_id%"'
			],
			
			'elements'	=> [

				'username'	=> [
					'label'			=> 'Account Username',
					'type'			=> 'text',
					'length'		=> 30,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|alpha_dash|unique:users,username|between:4,30', // this is wildcard for this table.
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
				'brief_bio'	=> [
					'label'			=> 'Your Brief Biography',
					'help' 			=> '',
					'type'			=> 'textarea',
					'rows' 			=> 6,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'locations'	=> [
					'label'			=> 'Locations Where Your Work Can Be Found',
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
				'subdomain'	=> [
					'label'			=> 'Dedicated Subdomain',
					'type'			=> 'text',
					'append' 		=> '.whereyart.net',
					'length'		=> 255,
					'attributes'	=> 'unique',
					'rules'			=>	'unique:users,subdomain|between:3,30',
					'display'		=>	'CRUD'
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
				'password'	=> [
					'label'			=> 'Reset Password',
					'type'			=> 'password',
					'length'		=> 255, // important. must be greater than 60 characters
					'attributes'	=> '',
					'rules'			=>	'alpha_dash|between:4,30',
					'display'		=>	'CU'
				]
			]
	];