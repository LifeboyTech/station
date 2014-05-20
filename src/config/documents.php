<?php

return [

'panel_options'	=> [
	'table'					=> 'documents',
	'single_item_name'		=> 'Document',
	'where' 				=> '',
	'has_timestamps'		=> TRUE,
	'has_position'			=> FALSE,
	'default_order_by'		=> 'name'
],

'elements'	=> [

	'name'	=> [
		'label'			=> 'Name',
		'type'			=> 'text',
		'length'		=> 255,
		'rules'			=>	'required',
		'display'		=>	'CRUDL'
	],
]];