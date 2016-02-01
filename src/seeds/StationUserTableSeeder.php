<?php

use Lifeboy\Station\Config\StationConfig as StationConfig;
use \Illuminate\Database\Seeder;

class StationUserTableSeeder extends \Illuminate\Database\Seeder {

	public function run()
	{
		$admin_user = array(

			'username'	=> 'admin',
			'password'	=> \App::make('hash')->make('admin'),
			'email'		=> StationConfig::app('root_admin_email'),
			'first_name'=> 'Johnny',
			'last_name'	=> 'Admin'
		);

		if (DB::table('users')->count() < 1){
			
			DB::table('users')->insert($admin_user);
		}
	}
}
