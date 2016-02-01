<?php namespace Lifeboy\Station\Models;

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
        return $this->hasMany('Lifeboy\Station\Models\User', 'id');
    }
}