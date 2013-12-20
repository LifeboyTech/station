<?php
namespace Canary\Station\Controllers;
use Illuminate\Routing\Controller;
use View, Config;

abstract class BaseController extends Controller{

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
        // Achieve that segment
        $this->base_uri = Config::get('station::_app.root_uri_segment');

        // Setup composed views and the variables that they require
        $this->beforeFilter( 'sessionFilter' , array('except' => array('create')) );
        //$composed_views = array( 'laravel-bootstrap::*' );
        //View::composer($composed_views, 'Davzie\LaravelBootstrap\Composers\Page');
    }

}