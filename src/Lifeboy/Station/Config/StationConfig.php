<?php namespace Lifeboy\Station\Config;

use Config;

class StationConfig {

	static function app($key = ''){

		$key = $key != '' ? '.'.$key : '';
		$is_vendor = self::is_vendor();
		$namespace = $is_vendor ? 'station_vendor_config' : 'station';
		$prefix = 'packages.lifeboy.station._app';
		return config($is_vendor ? $prefix.$key : $namespace.'::'.$prefix.$key);
	}

	/**
	 * for detecting if config is being loaded from internal build process, or outside request.
	 */
	static function is_vendor(){

		return strpos(realpath(__FILE__), '/vendor/') !== FALSE;
	}

	static function panel($panel_name){

		$is_vendor = self::is_vendor();
		$namespace = $is_vendor ? 'station_vendor_config' : 'station';
		$prefix = 'packages.lifeboy.station.';
		
		return Config::get($is_vendor ? $prefix.$panel_name : $namespace.'::'.$prefix.$panel_name);
	}
}