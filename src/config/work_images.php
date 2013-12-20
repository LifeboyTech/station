<?php

	return [
			'panel_options'	=> [
				'table'					=> 'work_images',
				'single_item_name'		=> 'Image',
				'where' 				=> '',
				'has_timestamps'		=> TRUE,
				'default_order_by'		=> 'position',
				'reorderable_by' 		=> 'position'
			],
			
			'elements'	=> [

				'name'	=> [
					'label'			=> 'Title / Caption',
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
					'display'		=> 'CRUD',
					'allow_upsize' 	=> TRUE,
					'sizes' 		=> [
						'original' 	=> ['label'=>'Original'],	
						'large'		=> ['label'=>'Large','size'=>'486x0'],
						'medium'	=> ['label'=>'Medium','size'=>'239x0'],
						'small'		=> ['label'=>'Small','size'=>'225x0']
					]
				],
				'credit'	=> [
					'label'			=> 'Photo Credit',
					'type'			=> 'text',
					'length'		=> 255,
					'attributes'	=> '',
					'rules'			=>	'',
					'display'		=>	'CRUDL'
				]
			]
	];