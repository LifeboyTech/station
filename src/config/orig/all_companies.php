<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'companies',	//if no tablename, uses panel name
				'single_item_name'		=> 'Company',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,	// Boolean for whether or not we'll be using timestamps
				'default_order_by'			=> 'name'
			],
			
			'elements'	=> [
				// These are the components that make up this panel
				'name'	=> [
					'label'			=> 'Company Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> 'unique|index',
					'rules'			=>	'required|unique:companies,name',
					'display'		=>	'CRUDL'
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