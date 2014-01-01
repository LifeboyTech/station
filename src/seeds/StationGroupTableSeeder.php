<?php

use Canary\Station\Config\StationConfig as StationConfig;

class StationGroupTableSeeder extends Seeder {

	public function run()
	{
		$group_list = array();

		$app = StationConfig::app();

		foreach($app['user_groups'] as $key => $group) // TODO!: change key?
		{
			$group_list[] = array('name'=>$key);
		}

		if (DB::table('groups')->count() < 1){
			
			DB::table('groups')->insert($group_list);
		}
	}

}
