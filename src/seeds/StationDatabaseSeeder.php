<?php 

use \Illuminate\Database\Seeder;

class StationDatabaseSeeder extends \Illuminate\Database\Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->call('StationUserTableSeeder');
		$this->call('StationGroupTableSeeder');
		$this->call('StationGroupUserTableSeeder');

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}