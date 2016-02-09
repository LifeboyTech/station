<?php 
	
	/**
	* This is the package-level configuration for Station .
	*/

	return [

		'name'						=> 'My Site Name', 			// The name of your app or site 
		'root_admin_email' 			=> 'me@mydomain.com',		// Email of the main administrator
		'root_uri_segment'			=> 'station', 				// The base url segment name. Ex. resolves to http://{host}/{root_uri_segment}/
		'panel_for_user_create' 	=> 'create_account',		// which panel do we use for creating a new user?
		'user_group_upon_register' 	=> 'anon', 					// when a new user registers, which group do we assign them?
		'css_override_file' 		=> '', 						// optional path to css file which will be loaded on every page request

		'media_options'	=> [

			'AWS'			=> [
				'bucket'	=> 'aws-bucket-name',
				'key'    	=> 'XXXXXXXXXXXXXXXXXXXX',
		    	'secret' 	=> 'XXXXXXXXXXXXXXXXXXXX'
			],
			'sizes' 		=> [

				'original'		=> ['label'=>'Original','size'=>'']
			]
		],

		'user_groups' => [							

			'admin' => [

				'starting_panel' 	=> 'welcome.L',
				'panels' 			=> [

					'demo_section'			=> ['name' => 'Section Header',		'is_header' => TRUE, 'icon' => 'glyphicon glyphicon-book'],
					'welcome'				=> ['name' => 'Welcome', 			'permissions' => 'CRUDL'],

					'admin_sections' 		=> ['name' => 'Administrative', 	'is_header' => TRUE, 'icon' => 'glyphicon glyphicon-user'],
					'users'					=> ['name' => 'Users', 				'permissions' => 'CRUDL'],
					'user_passwords'		=> ['name' => 'User Passwords', 	'permissions' => 'LU'],
					'groups'				=> ['name' => 'Groups', 			'permissions' => 'UL'],
					'my_account'			=> ['name' => 'My Account', 		'permissions' => 'U', 'uri_slug' => 'update/%user_id%'],
				]
			],

			'anon' => [

				'starting_panel' 	=> 'create_account.C',
				'panels' 			=> [

					'create_account' 		=> ['name' => 'Create An Account', 	'permissions' => 'C']
				]
			]
		]
	];