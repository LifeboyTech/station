<?php
namespace Canary\Station\Controllers;
use \Illuminate\Routing\Controller;
use Request, Redirect;
use Canary\Station\Config\StationConfig as StationConfig;

abstract class BaseController extends \Illuminate\Routing\Controller {

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct(){

        
    }
}