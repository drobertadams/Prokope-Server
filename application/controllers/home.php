<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for the home page. */
class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('home_page');
	}
}
