<?php namespace Lifeboy\Station\Controllers;

use View, Input, Redirect, Config, Session;
use Illuminate\Support\Facades\Auth as Auth;
use Lifeboy\Station\Filters\Session as Session_Filter;
use Illuminate\Http\Request;

class StationSessionController extends ObjectBaseController {

	public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

	/**
	 * Form for logging in
	 *
	 * @return Login form view or redirect 
	 */
	public function create()
	{
		//print_r(session()->all()); exit;
		if (Auth::check()) return $this->bootstrap();
		
		return View::make('station::sessions.create');
	}

	/**
	 * this is just a stub for post-login redirects. 
	 * make sure we hydrate the user's session with fresh new user data before passing them on.
	 */
	public function bootstrap()
	{

		if (Auth::guest()) return Redirect::to($this->base_uri.'login');

		Session_Filter::hydrate();

		if (Session::has('desired_uri')) {

			$desired_uri = Session::get('desired_uri');
			Session::forget('desired_uri');
			return Redirect::to($desired_uri);
		}

		$panel_uri = Session::get('user_data.starting_panel_uri');
		return Redirect::to($panel_uri);
	}

	/**
	 * Attempt to create a new user session
	 *
	 * @return Redirect to either home, intended route, or back to login form
	 */
	public function store()
	{
		if (Auth::check()) return $this->bootstrap();

		$username			= $this->request->input('username');
		$password			= $this->request->input('password');
		$remember_me		= (boolean) $this->request->input('remember_me');
		$username_col 		= strpos($username, '@') !== FALSE ? 'email' : 'username';
		$login_succeeded	= Auth::attempt(array($username_col => $username, 'password' => $password), $remember_me);
        
		if ($login_succeeded) {

			return $this->bootstrap();
		
		} else {

			$error_message = 'Your Username / Password Combo Was Not Found. Please try again.';
			return Redirect::to($this->base_uri.'sessions/create')->with('error', $error_message);
		}
	}

	/**
	 * Log the user out / destroy the session.
	 *
	 * @return redirect to login page
	 */
	public function destroy()
	{
		Auth::logout();
		Session::flush();

		return Redirect::to($this->base_uri.'login')->with('success', 'You are logged out.');
	}

}