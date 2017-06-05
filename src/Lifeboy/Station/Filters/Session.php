<?php namespace Lifeboy\Station\Filters;

use Redirect, Config, Request, Session as Laravel_Session;
use Illuminate\Support\Facades\Auth as Auth;
use Lifeboy\Station\Models\User as User;
use Lifeboy\Station\Models\Panel as Panel;
use Lifeboy\Station\Config\StationConfig as StationConfig;
use Closure;

class Session {

    /**
     * If the user is not logged in then we need to get them outta here.
     * 
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	$this->base_uri = StationConfig::app('root_uri_segment').'/';

        if (!Auth::check()) {

            Laravel_Session::put('desired_uri', '/'.Request::path());
            return Redirect::to($this->base_uri.'login');
        }

		if (!Laravel_Session::has('user_data')) $this->hydrate();

        return $next($request);
    }

    /**
     * populate the user_data
     *
     * @param  type  $param
     * @return void
     */
    static function hydrate(){

		$user_id			= Auth::user()->id;
		$user				= User::find($user_id);
		$group_names		= $user->groups->pluck('name');
		$primary_group		= $group_names->first();
		$app_groups			= StationConfig::app('user_groups');
		$starting_panel		= $app_groups[$primary_group]['starting_panel'];
		$starting_panel_uri	= Panel::config_to_uri($starting_panel);

		Laravel_Session::put('user_data', array(

            'id'                    => $user_id,
			'groups'				=> $group_names,
			'primary_group'			=> $primary_group,
			'starting_panel'		=> $starting_panel,
			'starting_panel_uri'	=> $starting_panel_uri,
            'name'                  => $user['first_name'].' '.$user['last_name'],
            'email'                 => $user['email'],
            'username'              => $user['username'],
            'gravatar_hash'         => md5($user['email'])
		));
    }
}
