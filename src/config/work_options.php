<?php

	return [
			'panel_options'	=> [
				'table'					=> 'work_options',
				'single_item_name'		=> 'Option',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,
				'default_order_by'		=> 'position',
				'reorderable_by' 		=> 'position'
			],
			
			'elements'	=> [

				'name'	=> [
					'label'			=> 'Option Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'dimensions'	=> [
					'label'			=> 'Dimensions',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'price'	=> [
					'label'			=> 'Price',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_price'	=> [
					'label'			=> 'Shipping Price',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'quantity_available'	=> [
					'label'			=> 'Quantity Available For Sale',
					'type'			=> 'text',
					'format' 		=> 'spinner',
					'rules'			=>	'numeric',
					'display'		=>	'CRUD'
				]
			]
	];