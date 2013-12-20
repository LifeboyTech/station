<?php 
	
	/**
	* This is the package-level configuration for Station
	*/

	return [

		'name'						=> 'My App', 		// The name of your app or site 
		'root_admin_email' 			=> 'admin@app.com',	// Email of the main administrator
		'root_uri_segment'			=> 'station', 		// The base url segment name. Ex. resolves to http://{host}/{root_uri_segment}/
		'panel_for_user_create' 	=> 'create_account',// which panel do we use for creating a new user?
		'user_group_upon_register' 	=> 'standard', 		// when a new user registers, which group do we assign them?

		'user_groups' => [								// The user groups:

			'admin' => [

				'starting_panel' 	=> 'users.L',
				'description'		=> 'Has access to all the things.'
			],
			'manager' => [

				'starting_panel' 	=> 'users.L',
				'description'		=> 'Has access to most things.'
			],
			'standard' => [

				'starting_panel' 	=> 'posts.L',
				'description'		=> 'Has limited access.'
			]
		],



		'panels' => [	// List of panels, in the order you want them to appear
						// Panel key is the file name for that panel's configuration. 
						// Ex. /app/config/packages/canary/station/{key}.php
			
			'admin_sections' => [
				'is_seperator'	=> TRUE,
				'for_group'	=> [
					'anon'				=>	FALSE,
					'admin'				=>	'Admin Sections',
					'manager'			=> 	'Admin Sections',
					'standard'			=>	'Profile'
				]
			],
			'create_account' => [
				'name'			=> 'Create An Account',
				'permissions'	=> [
					'anon'				=>	'C',
					'admin'				=>	'',
					'standard'			=>	'',
					'manager'			=> 	''
				]
			],
			'users'		=> [
				'name'			=> 'Users',
				'permissions'	=> [
					'anon'				=>	'',
					'admin'				=>	'CRUDL',
					'standard'			=>	'',
					'manager'			=> 	'CRUDL'
				]
			],
			'groups'		=> [
				'name'			=> 'Groups',
				'can_access'	=> 'admin',
				'permissions'	=> [
					'anon'				=>	'none',
					'admin'				=>	'L',
					'standard'			=>	'none',
					'manager'			=> 	'L'
				]
			],
			'blogging' => [
				'is_seperator'	=> TRUE,
				'for_group'	=> [
					'anon'				=>	FALSE,
					'admin'				=>	'Blogging',
					'manager'			=> 	'Blogging',
					'standard'			=>	'Blogging'
				]
			],
			'posts'		=> [
				'name'			=> 'Posts',
				'permissions'	=> [
					'anon'				=>	'none',
					'admin'				=>	'CRUDL',
					'standard'			=>	'CRUDL',
					'manager'			=> 	'CRUDL'
				]
			],
			'comments'		=> [
				'name'			=> 'Comments',
				'permissions'	=> [
					'anon'				=>	'none',
					'admin'				=>	'UDL',
					'standard'			=>	'UL',
					'manager'			=> 	'UDL'
				]
			]
		]
	];