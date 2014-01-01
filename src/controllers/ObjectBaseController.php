<?php namespace Canary\Station\Controllers;

use Config, View;
use Canary\Station\Config\StationConfig as StationConfig;

abstract class ObjectBaseController extends BaseController{

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
        View::share('app_data', StationConfig::app());
    }

}