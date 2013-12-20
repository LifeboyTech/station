<?php

	/**
	* This is a panel-level configuration
	*/

	return [
			'panel_options'	=> [
				'table'					=> 'orders',
				'single_item_name'		=> 'Cart Item',
				'has_timestamps'		=> TRUE,
				'default_order_by'		=> 'updated_at',
			],
			
			'elements'	=> [
				
				'user_id'	=> [
					'label'			=> 'user_id',
					'type'			=> 'integer',
					'attributes'	=> 'index',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'artist_id'	=> [
					'label'			=> 'artist_id',
					'type'			=> 'integer',
					'attributes'	=> 'index',
					'rules'			=> 'required',
					'display'		=> 'CRUDL'
				],
				'order_num'	=> [
					'label'			=> 'Order Number',
					'type'			=> 'text',
					'attributes'	=> 'index',
					'length'		=> 100,
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'session_id'	=> [
					'label'			=> 'Session ID',
					'type'			=> 'text',
					'length'		=> 100,
					'attributes'	=> 'index',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'work_id'	=> [
					'label'			=> 'work_id',
					'type'			=> 'integer',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'work_option_id'	=> [
					'label'			=> 'work_option_id',
					'type'			=> 'integer',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'work_option_name'	=> [
					'label'			=> 'option name',
					'type'			=> 'text',
					'length'		=> 299,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'work_name'	=> [
					'label'			=> ' Work Name',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'work_img'	=> [
					'label'			=> 'work img',
					'type'			=> 'text',
					'length'		=> 299,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'work_dimension'	=> [
					'label'			=> 'work dimension',
					'type'			=> 'text',
					'length'		=> 299,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'work_quantity'	=> [
					'label'			=> 'work quantity',
					'type'			=> 'integer',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],			
				'additional_info'	=> [
					'label'			=> 'Additional Information',
					'help' 			=> '',
					'type'			=> 'textarea',
					'rows' 			=> 4,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'order_date'	=> [
					'label'			=> 'Order Date',
					'type'			=> 'date',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	'CRUDL'
				],
				'shipping_price'	=> [
					'label'			=> 'Shipping Cost',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'work_price'	=> [
					'label'			=> 'Price',
					'type'			=> 'float',
					'format' 		=> 'money',
					'prepend'  		=> '$',
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_date'	=> [
					'label'			=> 'Shipping Date',
					'type'			=> 'date',
					'attributes'	=> '',
					'rules'			=>	'required', 
					'display'		=>	'CRUDL'
				],
				'shipping_email'	=> [
					'label'			=> 'email',
					'type'			=> 'email',
					'length'		=> 90,
					'attributes'	=> '',
					'rules'			=>	'required|email|max:90',
					'display'		=>	'CRUD'
				],
				'shipping_one'	=> [
					'label'			=> 'Ship to Name',
					'type'			=> 'text',
					'length'		=> 499,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_two'	=> [
					'label'			=> 'Ship to Address',
					'type'			=> 'text',
					'length'		=> 499,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_three'	=> [
					'label'			=> 'Address Line 2',
					'type'			=> 'text',
					'length'		=> 499,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_city'	=> [
					'label'			=> 'City',
					'type'			=> 'text',
					'length'		=> 499,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_state'	=> [
					'label'			=> 'State',
					'type'			=> 'text',
					'length'		=> 10,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_zip'	=> [
					'label'			=> 'Zip Code',
					'type'			=> 'text',
					'length'		=> 20,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'shipping_notes'	=> [
					'label'			=> 'Shipping Notes',
					'help' 			=> '',
					'type'			=> 'textarea',
					'rows' 			=> 4,
					'attributes'	=> '',
					'rules'			=> '',
					'display'		=> 'CRUD'
				],
				'paypal_status'	=> [
					'label'			=> 'PayPal Status',
					'type'			=> 'text',
					'length'		=> 199,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'paypal_paykey'	=> [
					'label'			=> 'PayPal payKey',
					'type'			=> 'text',
					'length'		=> 199,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				],
				'paypal_transactionid'	=> [
					'label'			=> 'PayPal transactionId',
					'type'			=> 'text',
					'length'		=> 199,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUD'
				]
			]
	];