<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for user functions. */
class User extends CI_Controller {

	/** View the users. */
	public function index()
	{
		redirect("/");
	}

	/** Handles user login. */
	public function login()
	{
		$this->quickauth->login($_POST['username'], $_POST['password']);

		redirect("/");
	}

	/** Handles user logout. */
	public function logout()
	{
		$this->quickauth->logout();

		redirect("/");
	}


	/** Displays a form to register the user. */
	public function register_form()
	{
		$this->load->view('user/register_form');
	}

	/** Handles the registration of new users. */
	public function register_handler()
	{
		// Grab the user info from the form.
		$userdata = array(
			'type' => array(1), // users go into the default (admin) group
			'username' => $_POST['username'],
			'password' => $_POST['password'],
			'firstname' => $_POST['firstname'],
			'lastname' => $_POST['lastname']
		);

		// Register the user.
		$this->quickauth->register($userdata);

		// Redirect to home.
		redirect("/");
	}

}

