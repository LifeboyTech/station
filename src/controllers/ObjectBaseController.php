<?php namespace Lifeboy\Station\Controllers;

use Config, View;
use Lifeboy\Station\Config\StationConfig as StationConfig;

abstract class ObjectBaseController extends BaseController{

    /**
     * The URL segment that can be used to access the system
     * @var string
     */
    protected $base_uri;

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        parent::__construct();
        $this->base_uri = StationConfig::app('root_uri_segment').'/';
        //$this->beforeFilter( 'sessionFilter' , array('except' => array('create')));
        View::share('app_data', StationConfig::app());
    }

}