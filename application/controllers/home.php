<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/** Controller for the home page. */
	public function index()
	{
		$this->load->view('home_page');
	}
}
