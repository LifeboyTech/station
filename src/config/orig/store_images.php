<?php

	return [
			'panel_options'	=> [
				'table'					=> 'store_images',
				'single_item_name'		=> 'Image',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,
				'default_order_by'		=> 'position',
				'reorderable_by' 		=> 'position'
			],
			
			'elements'	=> [

				'name'	=> [
					'label'			=> 'Caption',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'display'		=>	'CRUDL'
				],
				'image' => [
					'label'			=> 'Image',
					'type'			=> 'image',
					'rules'			=> 'required',
					'display'		=> 'CRUDL',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large Crop','size'=>'700x450'],
						'medium'	=> ['label'=>'Fixed Width','size'=>'500x0'],
						'small'		=> ['label'=>'Fixed Height','size'=>'0x250'],
						'thumb'		=> ['label'=>'Thumb With Letterbox', 'size'=>'100x200', 'letterbox'=>'#00FF00'],
					]
				],
				'credit'	=> [
					'label'			=> 'Photo Credit',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				],
				'store_id'	=> [
					'label'			=> 'Store ID',
					'type'			=> 'hidden',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'required',
					'default'		=>	'%parent_id%'
				]
			]
	];