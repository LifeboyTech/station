<?php namespace Lifeboy\Station\Controllers;

use Password, Input, Config, Session, Redirect, View, Hash, Auth, Lang;
use Illuminate\Http\Request;
use Lifeboy\Station\Config\StationConfig as StationConfig;

class StationUserController extends ObjectBaseController {

	public function __construct(Request $request)
    {
        parent::__construct();
        $this->request              = $request;
        $this->base_uri             = StationConfig::app('root_uri_segment').'/';
        $this->app                  = StationConfig::app();
        $this->data['is_logged_in'] = Auth::check();
    }

    public function do_reset_password(){

        $credentials = $this->request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
            Auth::login($user);
        });

        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::PASSWORD_RESET:
                return Redirect::to($this->base_uri.'home');
        }
    }

    /**
     * handles sending the user a message with password reset info
     *
     * @return void
     */
    public function forgot(){

    	switch ($response = Password::sendResetLink($this->request->only('email')))
        {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::RESET_LINK_SENT:
                return Redirect::back()->with('success', Lang::get($response));
        }

        return Redirect::back()->with('error', 'Please give us your email address.');
    }

    /**
     * display the form for password reset
     *
     * @param  string  $token
     * @return View
     */
    public function password_reset($token = null){

        if (is_null($token)) App::abort(404);

        return View::make('station::user.password_reset')->with('token', $token);
    }

    /**
     * this is the landing page for the forgotten password / reset password form
     * it just redirects to the login form.
     */
    public function reminded(){

        if (Session::has('success')){

            $status  = 'success';
            $message = 'We sent a password reset link to your email. Please take a look.';

        } else {

            $status  = 'error';
            $message = 'We could not find that email address in our system';
        }

        return Redirect::to($this->base_uri.'login')->with($status, $message);
    }
}
