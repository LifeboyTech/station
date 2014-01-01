<?php namespace Canary\Station\Models;

class User extends \User {

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