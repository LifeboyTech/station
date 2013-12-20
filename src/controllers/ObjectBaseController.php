<?php namespace Canary\Station\Controllers;

use Config;

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
        $this->base_uri = Config::get('station::_app.root_uri_segment').'/';
    }

}