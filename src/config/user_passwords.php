<?php

	return [
			'panel_options'	=> [
				'table'				=> 'users',	//if no tablename, uses panel name
				'single_item_name'	=> 'User',
				'has_timestamps'	=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'	=> 'username'
			],
			
			'elements'	=> [

				'username'	=> [
					'label'			=> 'Username',
					'type'			=> 'text',
					'length'		=> 30,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'L'
				],
				'password'	=> [
					'label'			=> 'Reset Password',
					'type'			=> 'password',
					'length'		=> 255, // important. must be greater than 60 characters
					'attributes'	=> '',
					'rules'			=>	'required|alpha_dash|between:4,30',
					'display'		=>	'U'
				]
			]
	];