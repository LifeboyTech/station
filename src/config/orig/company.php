<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'companies',	//if no tablename, uses panel name
				'single_item_name'		=> 'Company Profile',
				'where' 				=> 'user_id = "%user_id%"',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'			=> 'name',
				'no_data_force_create' 	=> TRUE,
				'no_data_alert' 		=> [

					'header' 	=> 'Thank you for creating a LocalGear account!',
					'body' 		=> 'Please start by providing some more info about your company and <b>reserve your subdomain!</b> '
								. 'You can return to this page at any time so feel free to skip some of the information requested.'
				]
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'name'	=> [
					'label'			=> 'My Company Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|unique:companies,name',
					'display'		=>	'CRUDL'
				],
				'img_company_hero' => [
					'label'			=> 'Company Image',
					'help' 			=> 'This is the big image on the company page',
					'type'			=> 'image',
					'rules'			=> 'required',
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large Crop','size'=>'700x450'],
						'medium'	=> ['label'=>'Fixed Width','size'=>'500x0'],
						'small'		=> ['label'=>'Fixed Height','size'=>'0x250'],
						'thumb'		=> ['label'=>'Thumb With Letterbox', 'size'=>'100x200', 'letterbox'=>'#00FF00'],
					]
				],
				'user_id'	=> [
					'label'			=> 'User ID',
					'type'			=> 'hidden',
					'default'		=> '%user_id%',
					'rules'			=> 'required',
					'display'		=> 'CRUDL'
				],
				'address'	=> [
					'label'			=> 'Primary Physical Address',
					'help' 			=> 'We\'ll use our hi-tech mapping system to find you!',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'zip'	=> [
					'label'			=> 'Zip Code',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'subdomain'	=> [
					'label'			=> 'Dedicated Subdomain',
					'type'			=> 'text',
					'append' 		=> '.localgear.com',
					'length'		=> 255,
					'attributes'	=> 'unique',
					'rules'			=>	'unique:companies,subdomain|between:3,30',
					'display'		=>	'CRUDL'
				],
				'year_originated'	=> [
					'label'			=> 'Year of Origination',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required|integer',
					'display'		=>	'CRUDL'
				],
				'brief_bio'	=> [
					'label'			=> 'Brief Bio',
					'help' 			=> 'Optional. Just some brief, fun facts about your company.',
					'type'			=> 'textarea',
					'rows' 			=> 3,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'facebook_url'	=> [
					'label'			=> 'Facebook Address',
					'type'			=> 'text',
					'prepend_icon'  => 'fui-facebook',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'twitter_url'	=> [
					'label'			=> 'Twitter Address or Username',
					'type'			=> 'text',
					'prepend_icon'  => 'fui-twitter',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'pinterest_url'	=> [
					'label'			=> 'Pinterest Address',
					'type'			=> 'text',
					'prepend_icon'  => 'fui-pinterest',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'stumbleupon_url'	=> [
					'label'			=> 'Stumbleupon Address',
					'type'			=> 'text',
					'prepend_icon'  => 'fui-stumbleupon',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'googleplus_url'	=> [
					'label'			=> 'Google Plus Address',
					'type'			=> 'text',
					'prepend_icon'  => 'fui-googleplus',
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				]

			]
			

	];