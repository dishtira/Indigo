<?php

class UserController extends IndigoController{

	public function showRegister()
	{
		return $this->showPage('register');
	}

	public function register()
	{
		$validator = Validator::make(Input::all(), array(
			'username'			=> 'required|unique:User',
			'password' 			=> 'required|same:confirmPassword',
			'confirmPassword'	=> 'required|same:password'
		));

		if ($validator->fails())
		{
			return Redirect::route('register-show')
					->withErrors($validator->errors()->add('location', 'register'))
					->withInput();
		}
		else
		{
			$username = Input::get('username');
			$password = Hash:: make(Input::get('password'));

			$create = User::create(array(
				'Username' 	=> $username,
				'Password' 	=> $password,
				'Role'		=> 'guest',
				'Active'	=> '0'
			));

			if($create)
			{
				return Redirect::route('home');
			}
			else
			{
				return "failed register";
			}
		}

	}

	public function doLogin()
	{
		$validator = Validator::make(Input::all(), array(
			'username'	=> 'required',
			'password'	=> 'required'
		));

		if ($validator->fails())
		{
			return Redirect::route('home')
				->withErrors($validator->errors()->add('location', 'login'))
				->withInput();
		}
		else
		{
			$auth = Auth::attempt(array(
				'username' 	=> Input::get('username'),
				'password' 	=> Input::get('password'),
				'active'	=> '1'
			), true);

			if($auth)
			{
				return Redirect::intended('/');
			}
			else
			{
				return Redirect::to('/')
					->with('errMessage',"Username/password invalid or not activated yet");
			}
		}
	}

	public function activateUser($username = null, $action = null)
	{
		if($action == "activate")
		{
			$active = "1";
		}
		else if ($action == "deactivate")
		{
			$active = "0";
		}

		$user = User::where('username', '=', $username)->first();
		$user->Active = $active;
		$user->save();
		return Redirect::route('adminPanel-show');
	}

	public function showEditUser($username = null)
	{
		$user = User::where('username', '=', $username)->first();
		return $this->showPageWith( 'editUser', array('user' => $user));
	}

	public function editUser()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$role = Input::get('role');

		$user = User::where('username', '=', $username)->first();
		if(Input::get('status') == "password")
		{
			$validator = Validator::make(Input::all(), array(
								'password' => 'required|same:confirmPassword',
								'confirmPassword' => 'required|same:password'
							));

			if ($validator->fails())
			{
				if(Input::get('location') != null && Input::get('location') == "changePassword")
				{
					return Redirect::route('changePassword-show')
						->withErrors($validator->errors()->add('location','changePassword'))
						->withInput();
				}
				else
				{					
					return Redirect::to('users/editUser/'.$username)
							->withErrors($validator->errors()->add('location','editUser'))
							->withInput();
				}
			}
			else
			{
				$user->Password = Hash::make($password);
				$user->save();
				if(Input::get('location') != null && Input::get('location') == "changePassword")
				{
					return Redirect::route('home');
				}
				else
				{
					return Redirect::route('adminPanel-show');
				}
			}
			
		}
		else if (Input::get('status') == "role")
		{
			$user->Role = $role;
			$user->save();
			return Redirect::route('adminPanel-show');
		}
	
	}

	public function showChangePassword()
	{
		$user = User::where('username', '=', Auth::user()->Username)->first();
		return $this->showPageWith('changePassword', array('user' => $user));
	}

	public function doLogout()
	{
		Auth::logout();
		return Redirect::route('home');
	}

}