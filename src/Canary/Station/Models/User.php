<?php namespace Canary\Station\Models;

use \App\User as App_user;

class User extends App_user {

	public function __construct()
    {
        parent::__construct();
        $this->guarded = array('id');
    }
	
    public function groups()
    {
        return $this->belongsToMany('Canary\Station\Models\Group');
    }
}