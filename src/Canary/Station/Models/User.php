<?php namespace Canary\Station\Models;

use DB;

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

    public function skills() // TODO: this is not a permanent solution for this project. need to include User in model build prob.
    {
        return $this->belongsToMany('\Skill');
    }

    public function works() // TODO: this is not a permanent solution for this project. need to include User in model build prob.
    {
        return $this->hasMany('\Work', 'artist_id');
    }

    public function data($order = 'random', $with_works = FALSE, $skill_id = FALSE, $not_id = FALSE){

        $order_by = $order == 'random' ? DB::raw('RAND()') : $order;

        if (!$skill_id){

            $data = User::with('skills');

        } else {

            $data = User::with(['skills' => function($query) use ($skill_id){

                $query->where('skills.id', '=', $skill_id);

            }])->hasWith('skills');
        }
        

        $data = $data->with(['groups' => function($query){

            $query->where('groups.id', '=', '2');

        }])->hasWith('groups');


        if ($with_works){

            $data = $data->with(['works' => function($query){

                $query->where('works.image', '!=', '')->orderBy('works.id', 'DESC');

            }])->hasWith('works');
        }

        $data = $not_id ? $data->where('id', '!=', $not_id) : $data;
        $data = $data->where('avatar', '!=', '')->where('is_published', '=', '1')->orderBy($order_by)->get();

        return $data;
    }

    public function scopeHasWith($query, $relation, $operator = '>=', $count = 1, $boolean = 'and')
    {
        // get relation
        $instance = $this->$relation();

        // create query that count relation
        $relationQuery = $instance->getRelationCountQuery($instance->getRelated()->newQuery());

        // If we actually found eager load of this relation so call it.
        $eagerLoads = $query->getEagerLoads();
        if (isset($eagerLoads[$relation]) && is_callable($eagerLoads[$relation]))
        {
            // call eager load to relation query
            call_user_func($eagerLoads[$relation], $relationQuery);
        }

        // merge binding from relation query to current query
        $query->mergeBindings($relationQuery->getQuery());

        // call where that count relation
        return $query->where(new \Illuminate\Database\Query\Expression('('.$relationQuery->toSql().')'), $operator, $count, $boolean);
    }
}