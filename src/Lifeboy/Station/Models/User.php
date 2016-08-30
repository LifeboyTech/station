<?php namespace Lifeboy\Station\Models;

use \App\User as App_user;

class User extends App_user {

	public function __construct()
    {
        parent::__construct();
        $this->guarded = array('id');
    }
	
    public function groups()
    {
        return $this->belongsToMany('Lifeboy\Station\Models\Group', 'group_user', 'user_id', 'group_id');
    }
}