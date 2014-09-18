<?php
namespace Canary\Station\Controllers;
use Illuminate\Routing\Controller;
use Request, Redirect;
use Canary\Station\Config\StationConfig as StationConfig;

abstract class BaseController extends \BaseController {

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct(){

        $this->beforeFilter(function() {
            
            $strict_domains = StationConfig::app('strict_domains');

            if ($strict_domains && count($strict_domains) > 0 && !in_array(Request::server('SERVER_NAME'), $strict_domains)){

                return Redirect::to('http://'.current($strict_domains).'/'.StationConfig::app('root_uri_segment'));
            }
        });
    }
}