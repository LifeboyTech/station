<?php

use \Illuminate\Database\Seeder;

class StationGroupUserTableSeeder extends \Illuminate\Database\Seeder {

	public function run()
	{
		$admin_user_join = array(

			'user_id' => 1,
			'group_id' => 1
		);

		if (DB::table('group_user')->count() < 1){
			
			DB::table('group_user')->insert($admin_user_join);
		}
		
	}

}
