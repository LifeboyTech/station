<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'site_copy',
				'single_item_name'		=> 'Page',
				'has_timestamps'		=> TRUE,	
				'default_order_by'		=> 'position',
				'nestable_by'			=> ['position'],
				'where'					=> 'area = "1"'
			],
			
			'elements'	=> [
			
				'name'	=> [
					'label'			=> 'Title',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'description'	=> [
					'label'			=> 'Body',
					'type'			=> 'textarea',
					'helper'		=> 'markdown',
					'rows' 			=> 26,
					'rules'			=>	'required',
					'display'		=>	'CRUD'
				],
				'area'	=> [
					'label'			=> 'Area',
					'type'			=> 'hidden',
					'rules'			=>	'required',
					'display'		=>	'CRUD',
					'default' 		=> '1'
				]
			]
	];