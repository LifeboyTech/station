<?php namespace Canary\Station\Models;

class Group extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';
	protected $guarded = array('id');

	public function users()
    {
        return $this->hasMany('Canary\Station\Models\User', 'id');
    }
}