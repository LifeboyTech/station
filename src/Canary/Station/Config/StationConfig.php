<?php namespace Canary\Station\Config;

use Config;

class StationConfig {

	static function app($key = '', $for_build = FALSE){

		$key = $key != '' ? '.'.$key : '';
		$is_vendor = self::is_vendor();
		$namespace = $is_vendor ? 'station_vendor_config' : 'station';
		return Config::get($is_vendor && !$for_build ? 'packages/canary/station/_app'.$key : $namespace.'::_app'.$key);
	}

	static function is_vendor(){

		return strpos(realpath(__FILE__), '/vendor/') !== FALSE;
	}

	static function panel($panel_name, $for_build = FALSE){

		$is_vendor = self::is_vendor();
		$namespace = $is_vendor ? 'station_vendor_config' : 'station';
		
		return Config::get($is_vendor && !$for_build ? 'packages/canary/station/'.$panel_name : $namespace.'::'.$panel_name);
	}
}