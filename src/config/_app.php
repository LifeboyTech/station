<?php 
	
	/**
	* This is the package-level configuration for Station .
	*/

	return [

		'name'						=> 'Where Y\'art', 			// The name of your app or site 
		'root_admin_email' 			=> 'benhirsch@gmail.com',	// Email of the main administrator
		'root_uri_segment'			=> 'station', 				// The base url segment name. Ex. resolves to http://{host}/{root_uri_segment}/
		'panel_for_user_create' 	=> 'create_account',		// which panel do we use for creating a new user?
		'user_group_upon_register' 	=> 'standard', 				// when a new user registers, which group do we assign them?
		'css_override_file' 		=> '/css/station.css', 		// optional path to css file which will be loaded on every page request

		'media_options'	=> [

			'AWS'			=> [
				'bucket'	=> 'timh.test2',
				'key'    	=> 'AKIAIEE3LRY6URO7IRQQ',
		    	'secret' 	=> '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'
			],
			'sizes' 		=> [

				'original'		=> ['label'=>'Original','size'=>'']
			]
		],

		'user_groups' => [							

			'admin' => [

				'starting_panel' 	=> 'works.L',
				'panels' 			=> [

					'work_sections' 		=> ['name' => 'Artist Works', 		'is_header' => TRUE, 'icon' => 'fui-image'],
					'works'					=> ['name' => 'Works', 				'permissions' => 'CRUDL'],
					'skills'				=> ['name' => 'Concentrations',		'permissions' => 'CRUDL'],
					'tags'					=> ['name' => 'Tags',			 	'permissions' => 'CRUDL'],
					'colors'				=> ['name' => 'Colors',			 	'permissions' => 'CRUDL'],

					'orders_sections' 		=> ['name' => 'Orders', 			'is_header' => TRUE, 'icon' => 'fui-credit-card'],
					'my_orders'				=> ['name' => 'My Cart', 			'permissions' => ''],
					'shipped_orders'		=> ['name' => 'Shipped Orders',		'permissions' => 'RL'],
					'pending_orders'		=> ['name' => 'Pending Orders',		'permissions' => 'URL', 'badge' => TRUE],

					'admin_sections' 		=> ['name' => 'Administrative', 	'is_header' => TRUE, 'icon' => 'fui-user'],
					'my_account'			=> ['name' => 'My Account', 		'permissions' => 'U', 'uri_slug' => 'update/%user_id%'],
					'users'					=> ['name' => 'Users / Artists', 	'permissions' => 'CRUDL'],
					'user_passwords'		=> ['name' => 'User Passwords', 	'permissions' => 'LU'],
					'groups'				=> ['name' => 'Groups', 			'permissions' => 'UL'],
					'media'					=> ['name' => 'Media', 				'permissions' => ''],

					'content_sections' 		=> ['name' => 'Content', 			'is_header' => TRUE, 'icon' => 'fui-new'],
					'home_page_slides'		=> ['name' => 'Home Page Slides',	'permissions' => 'CRUDL'],
					'about_copy'			=> ['name' => 'About Copy',			'permissions' => 'CRUDL'],
					'site_copy'				=> ['name' => 'Footer Copy',		'permissions' => 'CRUDL'],
					
				]
			],

			'artist' => [

				'starting_panel' 	=> 'my_works.L',
				'panels' 			=> [

					'my_works'				=> ['name' => 'My Works', 		'permissions' => 'CRUDL', 'icon' => 'fui-image'],
					'my_profile'			=> ['name' => 'My Profile', 	'permissions' => 'U', 'icon' => 'fui-user', 'uri_slug' => 'update/%user_id%'],
					'my_pending_orders'		=> ['name' => 'Pending Orders',	'permissions' => 'URL', 'badge' => TRUE],
					'my_shipped_orders'		=> ['name' => 'Shipped Orders',	'permissions' => 'RL']
				]
			],
			'standard' => [

				'starting_panel' 	=> 'my_account.U',
				'panels' 			=> [

					'my_account'			=> ['name' => 'My Account', 		'permissions' => 'U', 'uri_slug' => 'update/%user_id%'],
					'my_orders'				=> ['name' => 'My Cart', 			'permissions' => 'CRUD']
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